<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }

    $response = ["success" => false, "message" => ""];

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $taskId = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

        if (!$taskId) {
            $response["message"] = "Invalid task ID";
            echo json_encode($response);
            exit();
        }

        $success = query("DELETE FROM task WHERE id = ?", "deleteTask", $taskId);

        if ($success) {
            $response["success"] = true;
            $response["message"] = "Task deleted successfully";
        } else {
            $response["message"] = "Task not found or deletion failed";
        }
    } else {
        $response["message"] = "Invalid request method";
    }

    header('Content-Type: application/json');
    echo json_encode($response);