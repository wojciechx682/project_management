<?php

declare(strict_types=1);

if (isset($_GET["id"])) {

    $taskId = filter_var($_GET["id"], FILTER_VALIDATE_INT);

    if ($taskId === false) {
        echo json_encode(["success" => false, "message" => "Invalid task ID"]);
        exit();
    }

    $result = query("SELECT task.* FROM task WHERE task.id=?", "getTaskForEdit", $taskId);

    if ($result) {

        $task = $result->fetch(PDO::FETCH_ASSOC);

        $status = strtolower(str_replace(' ', '_', $task["status"]));

        echo json_encode([
            "success" => true,
            "task" => [
                "id" => $task["id"],
                "title" => $task["title"],
                "description" => $task["description"],
                "priority" => $task["priority"],
                "status" => $status,
                "dueDate" => $task["due_date"],
                "assigned_user_id" => $task["assigned_user_id"]
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Task not found"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Task ID not provided"]);
}
