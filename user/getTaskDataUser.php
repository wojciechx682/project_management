<?php
    require_once "../start-session.php";
    require_role("Team Member");

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

    json_success([
        'task' => [
            'id' => $task["id"],
            'title' => $task["title"],
            'status' => $task["status"],
        ],
    ]);
