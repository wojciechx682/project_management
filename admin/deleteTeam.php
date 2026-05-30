<?php
    require_once "../start-session.php";
    require_role("Admin");

    header('Content-Type: application/json; charset=UTF-8');

    $response = ["success" => false, "message" => ""];

    $_SESSION["message"] = "";

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $teamId = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

        if (!$teamId) {
            $response["message"] = "Invalid team ID";
            $_SESSION["message"] = '<span class="error">Invalid team ID</span>';
            json_response($response);
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
    json_response($response);