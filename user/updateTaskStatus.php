<?php
    require_once "../start-session.php";

    require_role("Team Member");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $response = ["success" => false];

        if (isset($_POST["id"]) && !empty($_POST["id"]) &&
            isset($_POST["status"]) && !empty($_POST["status"])) {

            // Walidacja danych
            $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
            $status = $_POST["status"];
            $validStatuses = ["not_started", "in_progress", "completed", "cancelled"];

            if (!in_array($status, $validStatuses)) {
                json_error("Invalid status");
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
                json_success([], "Task status updated successfully");
            } else {
                json_error("Failed to update task status");
            }

        } else {
            json_error("Task ID and status are required");
        }
    } else {
        json_error("Invalid request method", 405);
    }

