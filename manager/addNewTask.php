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
            isset($_POST["priority"]) && !empty($_POST["priority"]) &&
            isset($_POST["status"]) && !empty($_POST["status"]) &&
            isset($_POST["dueDate"]) && !empty($_POST["dueDate"]) &&
            isset($_POST["assignedUser"]) && !empty($_POST["assignedUser"]) &&
            isset($_SESSION["selected_project_id"]) && !empty($_SESSION["selected_project_id"]))  {

            // Pobranie danych + Walidacja zmiennych
            $title = htmlspecialchars($_POST["title"]);
            $description = htmlspecialchars($_POST["description"]);
            $priority = $_POST["priority"];
            $validPriorities = ["low", "medium", "high"];
            $status = $_POST["status"];
            $validStatuses = ["not_started", "in_progress", "completed", "cancelled"];
            $dueDate = $_POST["dueDate"];
            $dueDateObj = DateTime::createFromFormat('Y-m-d', $_POST["dueDate"]);
            $projectId = filter_var($_SESSION["selected_project_id"], FILTER_VALIDATE_INT);
            $assignedUser = filter_var($_POST["assignedUser"], FILTER_VALIDATE_INT);

            if (
                $title !== $_POST["title"] || strlen($title) > 255 ||
                $description !== $_POST["description"] || strlen($description) > 90 ||
                !in_array($_POST["priority"], $validPriorities) ||
                !in_array($_POST["status"], $validStatuses) ||
                !$dueDateObj || $dueDateObj->format('Y-m-d') !== $_POST["dueDate"] ||
                $projectId === false || $projectId != $_SESSION["selected_project_id"] ||
                $assignedUser === false || $assignedUser != $_POST["assignedUser"]
            ) {
                $response["error"] = "An error occurred. Please provide valid information";
                    echo json_encode($response);
                        exit();
            } else {

                switch ($priority) {
                    case "low":
                        $priorityFormatted = "Low";
                        break;
                    case "medium":
                        $priorityFormatted = "Medium";
                        break;
                    case "high":
                        $priorityFormatted = "High";
                        break;
                }

                switch ($status) {
                    case "not_started":
                        $statusFormatted = "Not Started";
                        break;
                    case "in_progress":
                        $statusFormatted = "In Progress";
                        break;
                    case "completed":
                        $statusFormatted = "Completed";
                        break;
                    case "cancelled":
                        $statusFormatted = "Cancelled";
                        break;
                }

                $task = [$title, $description, $priorityFormatted, $statusFormatted, $dueDate, $projectId, $assignedUser];
                $insertSuccessful = query("INSERT INTO task (id, title, description, priority, status, due_date, project_id, assigned_user_id, created_at, updated_at) VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s', '%s', NOW(), NOW())", "addNewTask", $task);

                if ($insertSuccessful) {

                    $taskId = $insertSuccessful;

                    // Pobierz utworzone timestampy z bazy
                    $taskDetails = query("SELECT task.created_at, task.updated_at, user.first_name, user.last_name FROM task JOIN user ON task.assigned_user_id = user.id WHERE task.id = '%s'", "getTaskInfo", $taskId);


                    if (!$taskDetails) {
                        echo json_encode(["success" => false, "message" => "Failed to fetch timestamps and user info"]);
                            exit();
                    }

                    // Sformatuj daty
                    $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $taskDetails["created_at"]);
                    $updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $taskDetails["updated_at"]);

                    $formattedCreatedAt = $createdAt->format('j F Y, H:i');
                    $formattedUpdatedAt = $updatedAt->format('j F Y, H:i');

                    $firstName = $taskDetails["first_name"];
                    $lastName = $taskDetails["last_name"];


                    //$team_name = query("SELECT team.name FROM team where team.id='%s'", "getTeamName", $teamId);
                    /*if(!$team_name) {
                        $response["error"] = "An error occurred. Please try again";
                            echo json_encode($response);
                                exit();
                    }*/

                    // Zwracamy odpowiedź w formacie JSON
                    echo json_encode([
                        "success" => true,
                        "id" => $taskId,
                        "title" => $title,
                        "description" => $description,
                        "priority" => $priorityFormatted,
                        "status" => $statusFormatted,
                        "due_date" => DateTime::createFromFormat('Y-m-d', $dueDate)->format('j F Y'),
                        "project_id" => $projectId,
                        "assigned_user_id" => $assignedUser,
                        "assigned_user_first_name" => $firstName,
                        "assigned_user_last_name" => $lastName,
                        "created_at" => $formattedCreatedAt,
                        "updated_at" => $formattedUpdatedAt,
                    ]);
                } else {
                    echo json_encode(["success" => false, "message" => "Failed to insert project"]);
                }
            }
        } else {
            echo json_encode(["success" => false, "message" => "All fields are required"]);
        }
    }
