<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }

    $response = ["success" => false, "message" => ""];

    $_SESSION["message"] = "";

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $teamId = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

        if (!$teamId) {
            $response["message"] = "Invalid team ID";
            $_SESSION["message"] = '<span class="error">Invalid team ID</span>';
            echo json_encode($response);
            exit();
        }

        $success = query("DELETE FROM team WHERE id = ?", "deleteTeam", $teamId);

        if ($success) {
            $response["success"] = true;
            $response["message"] = "Team deleted successfully";

            $_SESSION["message"] = '<span class="success">Team deleted successfully</span>';
        } else {
            $response["message"] = "Team not found or deletion failed";

            $_SESSION["message"] = '<span class="error">Team not found or deletion failed</span>';
        }
    } else {
        $response["message"] = "Invalid request method";

        $_SESSION["message"] = '<span class="error">Invalid request method</span>';
    }

    header('Content-Type: application/json');
    echo json_encode($response);