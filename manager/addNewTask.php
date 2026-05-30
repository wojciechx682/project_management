<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_error('Invalid request method', 405);
    }

    if (!isset($_POST["title"]) || empty($_POST["title"]) ||
        !isset($_POST["description"]) || empty($_POST["description"]) ||
        !isset($_POST["priority"]) || empty($_POST["priority"]) ||
        !isset($_POST["status"]) || empty($_POST["status"]) ||
        !isset($_POST["dueDate"]) || empty($_POST["dueDate"]) ||
        !isset($_POST["assignedUser"]) || empty($_POST["assignedUser"]) ||
        !isset($_SESSION["selected_project_id"]) || empty($_SESSION["selected_project_id"])) {
        json_error('All fields are required');
    }

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
        json_error('An error occurred. Please provide valid information');
    }

    switch ($priority) {
        case "low": $priorityFormatted = "Low"; break;
        case "medium": $priorityFormatted = "Medium"; break;
        case "high": $priorityFormatted = "High"; break;
    }

    switch ($status) {
        case "not_started": $statusFormatted = "Not Started"; break;
        case "in_progress": $statusFormatted = "In Progress"; break;
        case "completed": $statusFormatted = "Completed"; break;
        case "cancelled": $statusFormatted = "Cancelled"; break;
    }

    $task = [$title, $description, $priorityFormatted, $statusFormatted, $dueDate, $projectId, $assignedUser];
    $insertSuccessful = query(
        "INSERT INTO task (id, title, description, priority, status, due_date, project_id, assigned_user_id, created_at, updated_at) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
        "addNewTask",
        $task
    );

    if (!$insertSuccessful) {
        json_error('Failed to insert task');
    }

    $taskId = $insertSuccessful;

    $taskDetails = query(
        "SELECT task.created_at, task.updated_at, user.first_name, user.last_name FROM task JOIN user ON task.assigned_user_id = user.id WHERE task.id=?",
        "getTaskInfo",
        $taskId
    );

    if (!$taskDetails) {
        json_error('Failed to fetch timestamps and user info');
    }

    $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $taskDetails["created_at"]);
    $updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $taskDetails["updated_at"]);
    $formattedCreatedAt = $createdAt->format('j F Y, H:i');
    $formattedUpdatedAt = $updatedAt->format('j F Y, H:i');

    require_once __DIR__ . "/../notification_service.php";
    $pnameRow = query("SELECT name FROM project WHERE id = ?", "fetchOneAssoc", [$projectId]);
    if ($pnameRow) {
        notification_task_assigned($taskId, $assignedUser, $title, $pnameRow["name"], $projectId);
    }

    json_success([
        'id' => $taskId,
        'title' => $title,
        'description' => $description,
        'priority' => $priorityFormatted,
        'status' => $statusFormatted,
        'due_date' => DateTime::createFromFormat('Y-m-d', $dueDate)->format('j F Y'),
        'project_id' => $projectId,
        'assigned_user_id' => $assignedUser,
        'assigned_user_first_name' => $taskDetails["first_name"],
        'assigned_user_last_name' => $taskDetails["last_name"],
        'created_at' => $formattedCreatedAt,
        'updated_at' => $formattedUpdatedAt,
    ], 'Task added successfully');
