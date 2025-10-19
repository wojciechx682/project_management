<?php
    require_once "../start-session.php";

    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Team Member") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $response = ["success" => false];

        if (isset($_POST["id"]) && !empty($_POST["id"]) &&
            isset($_POST["status"]) && !empty($_POST["status"])) {

            // Walidacja danych
            $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
            $status = $_POST["status"];
            $validStatuses = ["not_started", "in_progress", "completed", "cancelled"];

            if (!in_array($status, $validStatuses)) {
                $response["message"] = "Invalid status";
                echo json_encode($response);
                exit();
            }

            // Formatowanie statusu do bazy danych (tak jak w updateTask.php)
            switch ($status) {
                case "not_started": $statusFormatted = "Not Started"; break;
                case "in_progress": $statusFormatted = "In Progress"; break;
                case "completed":   $statusFormatted = "Completed";   break;
                case "cancelled":   $statusFormatted = "Cancelled";   break;
            }

            // Aktualizacja statusu w bazie danych
            $updateSuccessful = query(
                "UPDATE task SET status=?, updated_at = NOW() WHERE id=?",
                "updateTaskStatus",
                [$statusFormatted, $id]
            );

            if ($updateSuccessful) {
                $response["success"] = true;
                $response["message"] = "Task status updated successfully";
            } else {
                $response["message"] = "Failed to update task status";
            }

        } else {
            $response["message"] = "Task ID and status are required";
        }

        echo json_encode($response);
    }

