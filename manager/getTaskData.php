<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    header('Content-Type: application/json; charset=UTF-8');

    if (!isset($_GET["id"])) {
        json_error('Task ID not provided');
    }

    $taskId = filter_var($_GET["id"], FILTER_VALIDATE_INT);

    if ($taskId === false) {
        json_error('Invalid task ID');
    }

    $result = query("SELECT task.* FROM task WHERE task.id=?", "getTaskForEdit", $taskId);

    if (!$result) {
        json_error('Task not found');
    }

    $task = $result->fetch(PDO::FETCH_ASSOC);
    $status = strtolower(str_replace(' ', '_', $task["status"]));

    json_success([
        'task' => [
            'id' => $task["id"],
            'title' => $task["title"],
            'description' => $task["description"],
            'priority' => $task["priority"],
            'status' => $status,
            'dueDate' => $task["due_date"],
            'assigned_user_id' => $task["assigned_user_id"],
        ],
    ]);
