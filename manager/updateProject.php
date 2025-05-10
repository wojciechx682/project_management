<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] === "POST") {

        $response = ["success" => false];

        if(isset($_POST["id"]) && !empty($_POST["id"]) &&
            isset($_POST["title"]) && !empty($_POST["title"]) &&
            isset($_POST["description"]) && !empty($_POST["description"]) &&
            isset($_POST["status"]) && !empty($_POST["status"]) &&
            isset($_POST["start_date"]) && !empty($_POST["start_date"]) &&
            isset($_POST["end_date"]) && !empty($_POST["end_date"]) &&
            isset($_POST["team_id"]) && !empty($_POST["team_id"])) {

            // Walidacja danych
            $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
            $title = htmlspecialchars($_POST["title"]);
            $description = htmlspecialchars($_POST["description"]);
            $status = $_POST["status"];
            $validStatuses = ["planned", "in_progress", "completed", "cancelled"];
            $startDate = $_POST["start_date"];
            $endDate = $_POST["end_date"];
            $teamId = filter_var($_POST["team_id"], FILTER_VALIDATE_INT);

            // === KLUCZOWA WERYFIKACJA ===
            if ($id === false) {
                $response["message"] = "Invalid Project ID format submitted.";
                    echo json_encode($response);
                        exit();
            }

            // Sprawdź, czy ID projektu z formularza zgadza się z tym z sesji
            if ($id !== $_SESSION["selected_project_id"]) {
                $response["message"] = "Project ID mismatch. Suspected data tampering. Please refresh and try again.";
                    echo json_encode($response);
                        exit();
            }

            if(!in_array($status, $validStatuses)) {
                $response["message"] = "Invalid status";
                    echo json_encode($response);
                        exit();
            }

            // Formatowanie statusu do bazy danych
            switch($status) {
                case "planned": $statusFormatted = "Planned"; break;
                case "in_progress": $statusFormatted = "In Progress"; break;
                case "completed": $statusFormatted = "Completed"; break;
                case "cancelled": $statusFormatted = "Cancelled"; break;
            }

            // Aktualizacja projektu w bazie danych
            $updateSuccessful = query("UPDATE project SET name=?, description=?, start_date=?, end_date=?, status=?, team_id=?, updated_at = NOW() WHERE id=?","updateProject", [$title, $description, $startDate, $endDate, $statusFormatted, $teamId, $_SESSION["selected_project_id"]]
            );

            if($updateSuccessful) {
                $response["success"] = true;
                $response["message"] = "Project updated successfully";
            } else {
                $response["message"] = "Failed to update project";
            }
        } else {
            $response["message"] = "All fields are required";
        }

        echo json_encode($response);
    }