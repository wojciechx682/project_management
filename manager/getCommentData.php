<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    header('Content-Type: application/json; charset=UTF-8');

    if (!isset($_GET["id"])) {
        json_error("Comment ID not provided");
    }

    $commentId = filter_var($_GET["id"], FILTER_VALIDATE_INT);

    if ($commentId === false) {
        json_error("Invalid comment ID");
    }

    $result = query("SELECT id, content, task_id FROM comment WHERE id = ?", "getCommentForEdit", $commentId);

    if (!$result) {
        json_error("Comment not found", 404);
    }

    $comment = $result->fetch(PDO::FETCH_ASSOC);

    json_success([
        "comment" => [
            "id" => $comment["id"],
            "content" => $comment["content"],
            "task_id" => $comment["task_id"],
        ],
    ]);