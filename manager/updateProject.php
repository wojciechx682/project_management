<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_error('Invalid request method', 405);
    }

    if (!isset($_POST["id"]) || empty($_POST["id"]) ||
        !isset($_POST["title"]) || empty($_POST["title"]) ||
        !isset($_POST["description"]) || empty($_POST["description"]) ||
        !isset($_POST["status"]) || empty($_POST["status"]) ||
        !isset($_POST["start_date"]) || empty($_POST["start_date"]) ||
        !isset($_POST["end_date"]) || empty($_POST["end_date"]) ||
        !isset($_POST["team_id"]) || empty($_POST["team_id"])) {
        json_error('All fields are required');
    }

    $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
    $title = htmlspecialchars($_POST["title"]);
    $description = htmlspecialchars($_POST["description"]);
    $status = $_POST["status"];
    $validStatuses = ["planned", "in_progress", "completed", "cancelled"];
    $startDate = $_POST["start_date"];
    $endDate = $_POST["end_date"];
    $teamId = filter_var($_POST["team_id"], FILTER_VALIDATE_INT);

    if (!in_array($status, $validStatuses)) {
        json_error('Invalid status');
    }

    switch ($status) {
        case "planned": $statusFormatted = "Planned"; break;
        case "in_progress": $statusFormatted = "In Progress"; break;
        case "completed": $statusFormatted = "Completed"; break;
        case "cancelled": $statusFormatted = "Cancelled"; break;
    }

    require_once __DIR__ . "/../notification_service.php";
    $oldProject = query("SELECT status, name FROM project WHERE id = ?", "fetchOneAssoc", [$id]);

    $updateSuccessful = query(
        "UPDATE project SET name=?, description=?, start_date=?, end_date=?, status=?, team_id=?, updated_at = NOW() WHERE id=?",
        "updateProject",
        [$title, $description, $startDate, $endDate, $statusFormatted, $teamId, $id]
    );

    if (!$updateSuccessful) {
        json_error('Failed to update project');
    }

    if ($oldProject && $oldProject["status"] !== $statusFormatted) {
        notification_project_status_changed($id, $oldProject["status"], $statusFormatted, $title);
    }

    json_success([], 'Project updated successfully');
