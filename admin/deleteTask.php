<?php
    require_once "../start-session.php";
    require_role("Admin");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        json_error('Invalid request method', 405);
    }

    $taskId = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

    if (!$taskId) {
        json_error('Invalid task ID');
    }

    $success = query("DELETE FROM task WHERE id = ?", "deleteTask", $taskId);

    if ($success) {
        json_success([], 'Task deleted successfully');
    }

    json_error('Task not found or deletion failed');
