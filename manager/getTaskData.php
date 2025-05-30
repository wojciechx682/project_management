<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }

    if(isset($_GET["id"])) {

        $taskId = filter_var($_GET["id"], FILTER_VALIDATE_INT);

        if($taskId === false) {
            echo json_encode(["success" => false, "message" => "Invalid task ID"]);
                exit();
        }

        // Pobierz dane projektu z bazy danych
        $result = query("SELECT task.* FROM task WHERE task.id=?", "getTaskForEdit", $taskId);

        //var_dump($result);

        if($result) {

            $task = $result->fetch(PDO::FETCH_ASSOC);

            // Konwersja statusu do formatu formularza
            $status = strtolower(str_replace(' ', '_', $task["status"]));

            echo json_encode([
                "success" => true,
                "task" => [
                    "id" => $task["id"],
                    "title" => $task["title"],
                    "description" => $task["description"],
                    "priority" => $task["priority"],
                    "status" => $status,
                    "dueDate" => $task["due_date"],
                    "assigned_user_id" => $task["assigned_user_id"]
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Task not found"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Task ID not provided"]);
    }