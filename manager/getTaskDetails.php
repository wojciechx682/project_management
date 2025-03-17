<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }
    if( $_SERVER['REQUEST_METHOD'] === "GET" ) {
        query("SELECT task.id, task.title, task.description, task.priority, task.status, task.due_date, task.project_id, task.assigned_user_id, task.created_at, task.updated_at, user.first_name, user.last_name, project.name FROM task JOIN user ON task.assigned_user_id = user.id JOIN project ON project.id = task.project_id WHERE task.project_id = '%s' AND task.id='%s'", "getTasks", [$_SESSION["selected_project_id"], $_GET["taskId"]]);
    }
