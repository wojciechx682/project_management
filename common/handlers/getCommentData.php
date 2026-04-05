<?php

declare(strict_types=1);

if (isset($_GET["id"])) {

    $commentId = filter_var($_GET["id"], FILTER_VALIDATE_INT);

    if ($commentId === false) {
        echo json_encode(["success" => false, "message" => "Invalid comment ID"]);
        exit();
    }

    $result = query("SELECT id, content, task_id FROM comment WHERE id = ?", "getCommentForEdit", $commentId);

    if ($result) {

        $comment = $result->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            "success" => true,
            "comment" => [
                "id" => $comment["id"],
                "content" => $comment["content"],
                "task_id" => $comment["task_id"]
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Comment not found"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Comment ID not provided"]);
}
