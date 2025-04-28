<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
            header("Location: ../index.php");
                exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $response = ["success" => false];

        if (isset($_POST["title"]) && !empty($_POST["title"]) &&
            isset($_POST["description"]) && !empty($_POST["description"]) &&
            isset($_POST["status"]) && !empty($_POST["status"]) &&
            isset($_POST["start_date"]) && !empty($_POST["start_date"]) &&
            isset($_POST["end_date"]) && !empty($_POST["end_date"]) &&
            isset($_POST["team_id"]) && !empty($_POST["team_id"])) {

            // Pobranie danych + Walidacja zmiennych
            $title = filter_var($_POST["title"], FILTER_SANITIZE_STRING);
            $description = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
            $status = $_POST["status"];
            $validStatuses = ["planned", "in_progress", "completed", "cancelled"];
            $startDate = $_POST["start_date"];
            $startDateObj = DateTime::createFromFormat('Y-m-d', $_POST["start_date"]);
            $endDate = $_POST["end_date"];
            $endDateObj = DateTime::createFromFormat('Y-m-d', $_POST["end_date"]);
            $teamId = filter_var($_POST["team_id"], FILTER_VALIDATE_INT);

            if (
                $title === false || $title !== $_POST["title"] || strlen($title) > 255 ||
                $description === false || $description !== $_POST["description"] || strlen($description) > 1000 ||
                !in_array($_POST["status"], $validStatuses) ||
                !$startDateObj || !$endDateObj || $startDateObj->format('Y-m-d') !== $_POST["start_date"] || $endDateObj->format('Y-m-d') !== $_POST["end_date"] ||
                $teamId === false || $teamId != $_POST["team_id"]
            ) {
                $response["error"] = "An error occurred. Please provide valid information";
                    echo json_encode($response);
                        exit();
            } else {
                $project = [$title, $description, $startDate, $endDate, $status, $teamId];
                $insertSuccessful = query("INSERT INTO project (id, name, description, start_date, end_date, status, team_id, created_at, updated_at) 
				                                               VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s', NOW(), NOW())", "addNewProject", $project);
                if ($insertSuccessful) {
                    $projectId = $insertSuccessful;
                    // Zwracamy odpowiedź w formacie JSON
                    //echo json_encode(["success" => true]);

                    $team_name = query("SELECT team.name FROM team where team.id='%s'", "getTeamName", $teamId);

                    echo json_encode([
                        "success" => true,
                        "id" => $projectId,
                        "title" => $title,
                        "description" => $description,
                        "start_date" => $startDate,
                        "end_date" => $endDate,
                        "status" => $status,
                        "team_name" => $team_name
                    ]);
                } else {
                    echo json_encode(["success" => false, "message" => "Failed to insert project"]);
                }
            }
        } else {
            echo json_encode(["success" => false, "message" => "All fields are required"]);
        }
    }
