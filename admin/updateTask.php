<?php
    require_once "../start-session.php";
    require_role("Admin");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_error('Invalid request method', 405);
    }

    if (!isset($_POST["id"]) || empty($_POST["id"]) ||
        !isset($_POST["title"]) || empty($_POST["title"]) ||
        !isset($_POST["description"]) || empty($_POST["description"]) ||
        !isset($_POST["priority"]) || empty($_POST["priority"]) ||
        !isset($_POST["status"]) || empty($_POST["status"]) ||
        !isset($_POST["due_date"]) || empty($_POST["due_date"]) ||
        !isset($_POST["assigned_user_id"]) || empty($_POST["assigned_user_id"])) {
        json_error('All fields are required');
    }

    $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
    $title = htmlspecialchars($_POST["title"]);
    $description = htmlspecialchars($_POST["description"]);
    $priority = $_POST["priority"];
    $validPriorities = ["low", "medium", "high"];
    $status = $_POST["status"];
    $validStatuses = ["not_started", "in_progress", "completed", "cancelled"];
    $dueDate = $_POST["due_date"];
    $assignedUserId = filter_var($_POST["assigned_user_id"], FILTER_VALIDATE_INT);

    if (!in_array($priority, $validPriorities)) {
        json_error('Invalid priority');
    }

    if (!in_array($status, $validStatuses)) {
        json_error('Invalid status');
    }

    switch ($priority) {
        case "low": $priorityFormatted = "Low"; break;
        case "medium": $priorityFormatted = "Medium"; break;
        case "high": $priorityFormatted = "High"; break;
    }

    switch ($status) {
        case "not_started": $statusFormatted = "Not Started"; break;
        case "in_progress": $statusFormatted = "In Progress"; break;
        case "completed": $statusFormatted = "Completed"; break;
        case "cancelled": $statusFormatted = "Cancelled"; break;
    }

    require_once __DIR__ . "/../notification_service.php";
    $oldTask = query("SELECT assigned_user_id, title, project_id FROM task WHERE id = ?", "fetchOneAssoc", [$id]);

    $updateSuccessful = query(
        "UPDATE task SET title=?, description=?, priority=?, status=?, due_date=?, assigned_user_id=?, updated_at = NOW() WHERE id=?",
        "updateTask",
        [$title, $description, $priorityFormatted, $statusFormatted, $dueDate, $assignedUserId, $id]
    );

    if (!$updateSuccessful) {
        json_error('Failed to update task');
    }

    if ($oldTask && (int) $oldTask["assigned_user_id"] !== (int) $assignedUserId) {
        $pnameRow = query("SELECT name FROM project WHERE id = ?", "fetchOneAssoc", [$oldTask["project_id"]]);
        if ($pnameRow) {
            notification_task_reassigned($id, $assignedUserId, $title, $pnameRow["name"], $oldTask["project_id"]);
        }
    }

    json_success([], 'Task updated successfully');
