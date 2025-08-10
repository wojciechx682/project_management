<?php
require_once "../start-session.php";

// Sprawdzenie uprawnień
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
    echo json_encode(["success" => false, "message" => "Unauthorized access"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ["success" => false];

    if (isset($_POST["project-id"])) {
        $projectId = $_POST["project-id"];

        // Walidacja ID projektu
        if (!filter_var($projectId, FILTER_VALIDATE_INT)) {
            $response["message"] = "Invalid project ID";
            echo json_encode($response);
            exit();
        }

        // Usuwanie powiązanych zadań (jeśli istnieją)
        $deleteTasksResult = query(
            "DELETE FROM task WHERE project_id = ?",
            null,
            [$projectId]
        );

        // Usuwanie projektu
        $deleteProjectResult = query(
            "DELETE FROM project WHERE id = ?",
            null,
            [$projectId]
        );

        if ($deleteProjectResult === true) {
            $response["success"] = true;
            $response["message"] = "Project deleted successfully";
        } else {
            $response["message"] = "Failed to delete project: Database error";
        }
    } else {
        $response["message"] = "Project ID not provided";
    }
} else {
    $response["message"] = "Invalid request method";
}

echo json_encode($response);
?>