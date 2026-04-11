<?php

/**
 * Powiadomienia w aplikacji — wywoływane po udanych operacjach CRUD.
 * Wymaga wcześniejszego załadowania start-session.php (funkcja query() z functions.php).
 *
 * notification_service.php -->
 * Tu są wszystkie funkcje, które tworzą powiadomienia (albo listę odbiorców). To „serce” systemu.
 */

// Niskopoziomowy INSERT jednego wiersza do notification.
function notification_insert($userId, $type, $title, $body, $taskId, $projectId, $teamId)
{
    if (!$userId || $userId <= 0) {
        return false;
    }

    $taskId = $taskId ?: null;
    $projectId = $projectId ?: null;
    $teamId = $teamId ?: null;

    $ok = query(
        "INSERT INTO notification (user_id, type, title, body, task_id, project_id, team_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
        "addNotificationLastId",
        [$userId, $type, $title, $body, $taskId, $projectId, $teamId]
    );

    return $ok !== false;
}

// Lista ID Team Memberów w zespole (do masowych powiadomień).
function notification_user_ids_for_team($teamId)
{
    $rows = query(
        "SELECT DISTINCT u.id AS user_id FROM team_user tu
         JOIN user u ON u.id = tu.user_id
         WHERE tu.team_id = ? AND u.role = 'Team Member'",
        "fetchAllAssoc",
        [$teamId]
    );
    if ($rows === null) {
        return [];
    }
    $out = [];
    foreach ($rows as $r) {
        $out[] = (int) $r["user_id"];
    }
    return $out;
}

// Lista ID Team Memberów powiązanych z projektem (przez team_id projektu).
function notification_user_ids_for_project($projectId)
{
    $rows = query(
        "SELECT DISTINCT u.id AS user_id FROM project p
         JOIN team_user tu ON tu.team_id = p.team_id
         JOIN user u ON u.id = tu.user_id
         WHERE p.id = ? AND u.role = 'Team Member'",
        "fetchAllAssoc",
        [$projectId]
    );
    if ($rows === null) {
        return [];
    }
    $out = [];
    foreach ($rows as $r) {
        $out[] = (int) $r["user_id"];
    }
    return $out;
}

// Powiadomienie o nowym przypisaniu zadania.
function notification_task_assigned($taskId, $assigneeUserId, $taskTitle, $projectName, $projectId)
{
    $title = "New task assigned";
    $body = "You were assigned: \"" . $taskTitle . "\" in project \"" . $projectName . "\".";
    return notification_insert((int) $assigneeUserId, "task_assigned", $title, $body, (int) $taskId, (int) $projectId, null);
}

// Gdy zmieni się osoba przypisana do zadania (inny assignee).
function notification_task_reassigned($taskId, $newAssigneeUserId, $taskTitle, $projectName, $projectId)
{
    $title = "Task assigned to you";
    $body = "You are now assigned to \"" . $taskTitle . "\" (" . $projectName . ").";
    return notification_insert((int) $newAssigneeUserId, "task_reassigned", $title, $body, (int) $taskId, (int) $projectId, null);
}

// Gdy użytkownik zostaje dodany do zespołu.
function notification_team_joined($userId, $teamName, $teamId)
{
    $title = "Added to a team";
    $body = "You were added to the team \"" . $teamName . "\".";
    return notification_insert((int) $userId, "team_joined", $title, $body, null, null, (int) $teamId);
}

// Gdy zmieni się nazwa zespołu — po jednym wpisie na każdego członka (Team Member).
function notification_team_renamed($teamId, $oldName, $newName)
{
    $userIds = notification_user_ids_for_team($teamId);
    foreach ($userIds as $uid) {
        $title = "Team renamed";
        $body = "Team \"" . $oldName . "\" was renamed to \"" . $newName . "\".";
        notification_insert($uid, "team_renamed", $title, $body, null, null, (int) $teamId);
    }
}

// Gdy zmieni się status projektu — dla wszystkich Team Memberów projektu.
function notification_project_status_changed($projectId, $oldStatus, $newStatus, $projectName)
{
    if ($oldStatus === $newStatus) {
        return;
    }
    $userIds = notification_user_ids_for_project($projectId);
    foreach ($userIds as $uid) {
        $title = "Project status updated";
        $body = "Project \"" . $projectName . "\" status changed from \"" . $oldStatus . "\" to \"" . $newStatus . "\".";
        notification_insert($uid, "project_status_changed", $title, $body, null, (int) $projectId, null);
    }
}

// Gdy PM lub Admin doda komentarz do zadania — powiadomienie dla osoby przypisanej do zadania (jeśli to nie ten sam co autor komentarza).
function notification_comment_on_task_pm($taskId, $commentAuthorId, $taskTitle, $projectName, $assigneeUserId, $projectId)
{
    if ((int) $assigneeUserId === (int) $commentAuthorId) {
        return;
    }
    $title = "New comment on your task";
    $body = "A manager added a comment on task \"" . $taskTitle . "\" (" . $projectName . ").";
    notification_insert((int) $assigneeUserId, "comment_on_task", $title, $body, (int) $taskId, (int) $projectId, null);
}

/**
 *Przypomnienia o terminie: zadania w ciągu 3 dni, nie zakończone.
 */
// Dla zadań z terminem w ciągu 3 dni i statusem nie Completed/Cancelled: tworzy co najwyżej jedno nieprzeczytane task_due_soon na zadanie (bez duplikatu).
function notification_sync_due_reminders_for_user($userId)
{
    $tasks = query(
        "SELECT id, title, due_date FROM task
         WHERE assigned_user_id = ?
           AND due_date IS NOT NULL
           AND due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)
           AND status NOT IN ('Completed', 'Cancelled')",
        "fetchAllAssoc",
        [$userId]
    );
    if ($tasks === null) {
        return;
    }

    foreach ($tasks as $t) {
        $taskId = (int) $t["id"];
        $dup = query(
            "SELECT id FROM notification WHERE user_id = ? AND type = 'task_due_soon' AND task_id = ? AND read_at IS NULL LIMIT 1",
            "fetchOneAssoc",
            [$userId, $taskId]
        );
        if ($dup) {
            continue;
        }
        $due = $t["due_date"];
        $title = "Task due soon";
        $body = "Task \"" . $t["title"] . "\" is due on " . $due . ".";
        notification_insert((int) $userId, "task_due_soon", $title, $body, $taskId, null, null);
    }
}
