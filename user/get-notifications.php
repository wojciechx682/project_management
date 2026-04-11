<?php

    // Przypomnienia o terminie — przy każdym pobraniu listy (get-notifications.php) wywoływane jest notification_sync_due_reminders_for_user(): dla zadań przypisanych do użytkownika z due_date w ciągu 3 dni i statusem innym niż Completed / Cancelled tworzone jest co najwyżej jedno nieprzeczytane powiadomienie task_due_soon na zadanie (bez duplikatów).

    // API dla przeglądarki — user/get-notifications.php i user/mark-notification-read.php
    // Team Member pobiera listę i oznacza jako przeczytane.


    require_once "../start-session.php";

    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Team Member") {
        http_response_code(403);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(["success" => false, "message" => "Forbidden"]);
        exit();
    }

    require_once dirname(__DIR__) . "/notification_service.php";

    $userId = (int) $_SESSION["id"];

    notification_sync_due_reminders_for_user($userId);

    $rows = query(
        "SELECT id, type, title, body, task_id, project_id, team_id, read_at, created_at
         FROM notification
         WHERE user_id = ?
         ORDER BY created_at DESC
         LIMIT 50",
        "fetchAllAssoc",
        [$userId]
    );

    $unread = query(
        "SELECT COUNT(*) AS c FROM notification WHERE user_id = ? AND read_at IS NULL",
        "getProjectsCount",
        [$userId]
    );

    $items = $rows === null ? [] : $rows;
    $unreadCount = 0;
    if ($unread !== null) {
        $unreadCount = (int) $unread;
    }

    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode([
        "success" => true,
        "unread_count" => $unreadCount,
        "items" => $items,
    ]);
