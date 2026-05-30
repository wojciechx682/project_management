<?php
    require_once "../start-session.php";
    require_role("Admin");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_error('Invalid request method', 405);
    }

    if (!isset($_POST["task_id"]) || empty($_POST["task_id"]) ||
        !isset($_POST["content"]) || empty($_POST["content"])) {
        json_error('All fields are required.');
    }

    $taskId = filter_var($_POST["task_id"], FILTER_VALIDATE_INT);
    $content = htmlspecialchars(trim($_POST["content"]));

    if ($taskId === false || $taskId != $_POST["task_id"]) {
        json_error('Invalid task ID.');
    }

    if ($content !== trim($_POST["content"]) || strlen($content) < 10 || strlen($content) > 255) {
        json_error('Comment must contain between 10 and 255 characters and no special characters.');
    }

    $commentData = [$taskId, $_SESSION["id"], $content];
    $insertSuccessful = query(
        "INSERT INTO comment (id, task_id, user_id, content, created_at) VALUES (NULL, ?, ?, ?, NOW())",
        "addNewComment",
        $commentData
    );

    if (!$insertSuccessful) {
        json_error('Failed to add comment. Please try again.');
    }

    require_once __DIR__ . "/../notification_service.php";
    $ctx = query(
        "SELECT task.assigned_user_id, task.title, project.name AS project_name, project.id AS project_id FROM task JOIN project ON project.id = task.project_id WHERE task.id = ?",
        "fetchOneAssoc",
        [$taskId]
    );
    if ($ctx && isset($_SESSION["role"]) && ($_SESSION["role"] === "Project Manager" || $_SESSION["role"] === "Admin")) {
        notification_comment_on_task_pm($taskId, $_SESSION["id"], $ctx["title"], $ctx["project_name"], $ctx["assigned_user_id"], $ctx["project_id"]);
    }

    json_success([
        'comment' => [
            'task_id' => $taskId,
            'user_id' => $_SESSION["id"],
            'content' => $content,
            'created_at' => date("j F Y, H:i"),
        ],
    ], 'Comment added successfully');
