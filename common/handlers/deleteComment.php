<?php

declare(strict_types=1);

$response = ["success" => false, "message" => ""];

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

    $commentId = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

    if (!$commentId) {
        $response["message"] = "Invalid comment ID";
        echo json_encode($response);
        exit();
    }

    $success = query("DELETE FROM comment WHERE id = ?", "deleteComment", $commentId);

    if ($success) {
        $response["success"] = true;
        $response["message"] = "Comment deleted successfully";
    } else {
        $response["message"] = "Comment not found or deletion failed";
    }
} else {
    $response["message"] = "Invalid request method";
}

header('Content-Type: application/json');
echo json_encode($response);
