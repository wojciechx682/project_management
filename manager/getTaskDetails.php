<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
            header("Location: ../index.php");
                exit();
    }
    if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET["taskId"])) {
        // Walidacja ID zadania
        $taskId = filter_var($_GET["taskId"], FILTER_VALIDATE_INT);
        if ($taskId === false || $taskId <= 0) {
            header("Content-Type: application/json");
                echo json_encode(["error" => "Invalid task ID"]);
                    exit();
        }

        // Pobierz ID projektu z sesji
        $projectId = $_SESSION["selected_project_id"] ?? null;
        if (!$projectId) {
            header("Content-Type: application/json");
                echo json_encode(["error" => "Project not selected"]);
                    exit();
        }

        $task = query("SELECT task.id FROM task WHERE task.id = '%s' AND task.project_id = '%s'","verifyTaskExists", [$taskId, $projectId]);
        if (!$task) {
            header("Content-Type: application/json");
                echo json_encode(["error" => "Task not found"]);
                    exit();
        }

        // Wykonaj zapytanie
        $result = query("SELECT task.id, task.title, task.description, task.priority, task.status, task.due_date, task.project_id, task.assigned_user_id, task.created_at, task.updated_at, user.first_name, user.last_name, project.name FROM task JOIN user ON task.assigned_user_id = user.id JOIN project ON project.id = task.project_id WHERE task.project_id = '%s' AND task.id='%s'","getTasks", [$projectId, $taskId]);

        /*if ($result === null) {
            header("Content-Type: application/json");
                echo json_encode(["error" => "Task not found"]);
                    exit();
        } elseif ($result === false) {
            header("Content-Type: application/json");
                echo json_encode(["error" => "Database error"]);
                    exit();
        }*/

        /*if ($result === false || $result === null) {
            // Nie znaleziono zadania lub błąd
            header("HTTP/1.1 404 Not Found");
            header("Content-Type: text/html; charset=UTF-8");
            echo '<span class="error">Task not found</span>';
            exit();
        }*/

        // Sprawdź czy znaleziono zadanie
        /*if ($result === null || $result === false) {
            header("Content-Type: text/html");
            echo '<div class="error">Task not found or you don\'t have access</div>';
            exit();
        }*/

    } else {
        header("Content-Type: application/json");
            echo json_encode(["error" => "Invalid request method or missing parameters"]);
                exit();
    }

