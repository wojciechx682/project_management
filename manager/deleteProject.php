<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    header('Content-Type: application/json; charset=UTF-8');

    $response = ["success" => false, "message" => ""];

    $_SESSION["message"] = "";

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $projectId = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

        if (!$projectId) {
            $response["message"] = "Invalid project ID";
            $_SESSION["message"] = '<span class="error">Invalid project ID</span>';
            json_response($response);
        }

        $success = query("DELETE FROM project WHERE id = ?", "deleteProject", $projectId);

        if ($success) {
            $response["success"] = true;
            $response["message"] = "Project deleted successfully";

            $_SESSION["message"] = '<span class="success">Project deleted successfully</span>';
        } else {
            $response["message"] = "Project not found or deletion failed";

            $_SESSION["message"] = '<span class="error">Project not found or deletion failed</span>';
        }
    } else {
        $response["message"] = "Invalid request method";

        $_SESSION["message"] = '<span class="error">Invalid request method</span>';
    }
    json_response($response);