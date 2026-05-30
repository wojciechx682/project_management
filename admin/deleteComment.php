<?php
    require_once "../start-session.php";
    require_role("Admin");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        json_error('Invalid request method', 405);
    }

    $commentId = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

    if (!$commentId) {
        json_error('Invalid comment ID');
    }

    $success = query("DELETE FROM comment WHERE id = ?", "deleteComment", $commentId);

    if ($success) {
        json_success([], 'Comment deleted successfully');
    }

    json_error('Comment not found or deletion failed');
