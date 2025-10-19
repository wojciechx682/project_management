<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Team Member") {
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

            //$status = strtolower(str_replace(' ', '_', $task["status"]));
            $status = $task["status"];

            echo json_encode([
                "success" => true,
                "task" => [
                    "id" => $task["id"],
                    "title" => $task["title"],
                    "status" => $status
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Task not found"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Task ID not provided"]);
    }