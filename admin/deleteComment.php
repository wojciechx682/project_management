<?php
    require_once "../start-session.php";
    require_role("Admin");

    header('Content-Type: application/json; charset=UTF-8');

    $response = ["success" => false, "message" => ""];

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $commentId = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

        if (!$commentId) {
            $response["message"] = "Invalid comment ID";
            json_response($response);
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
    json_response($response);