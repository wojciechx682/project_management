<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_error('Invalid request method', 405);
    }

    if (!isset($_POST["title"]) || empty($_POST["title"]) ||
        !isset($_POST["description"]) || empty($_POST["description"]) ||
        !isset($_POST["status"]) || empty($_POST["status"]) ||
        !isset($_POST["start_date"]) || empty($_POST["start_date"]) ||
        !isset($_POST["end_date"]) || empty($_POST["end_date"]) ||
        !isset($_POST["team_id"]) || empty($_POST["team_id"])) {
        json_error('All fields are required');
    }

    $title = htmlspecialchars($_POST["title"]);
    $description = htmlspecialchars($_POST["description"]);
    $status = $_POST["status"];
    $validStatuses = ["planned", "in_progress", "completed", "cancelled"];
    $startDate = $_POST["start_date"];
    $startDateObj = DateTime::createFromFormat('Y-m-d', $_POST["start_date"]);
    $endDate = $_POST["end_date"];
    $endDateObj = DateTime::createFromFormat('Y-m-d', $_POST["end_date"]);
    $teamId = filter_var($_POST["team_id"], FILTER_VALIDATE_INT);

    if (
        $title !== $_POST["title"] || strlen($title) > 255 ||
        $description !== $_POST["description"] || strlen($description) > 90 ||
        !in_array($_POST["status"], $validStatuses) ||
        !$startDateObj || !$endDateObj ||
        $startDateObj->format('Y-m-d') !== $_POST["start_date"] ||
        $endDateObj->format('Y-m-d') !== $_POST["end_date"] ||
        $teamId === false || $teamId != $_POST["team_id"]
    ) {
        json_error('An error occurred. Please provide valid information');
    }

    switch ($status) {
        case "planned": $statusFormatted = "Planned"; break;
        case "in_progress": $statusFormatted = "In Progress"; break;
        case "completed": $statusFormatted = "Completed"; break;
        case "cancelled": $statusFormatted = "Cancelled"; break;
    }

    $project = [$title, $description, $startDate, $endDate, $statusFormatted, $teamId];
    $insertSuccessful = query(
        "INSERT INTO project (id, name, description, start_date, end_date, status, team_id, created_at, updated_at) VALUES (NULL, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
        "addNewProject",
        $project
    );

    if (!$insertSuccessful) {
        json_error('Failed to insert project');
    }

    $projectId = $insertSuccessful;
    $team_name = query("SELECT team.name FROM team where team.id=?", "getTeamName", $teamId);

    if (!$team_name) {
        json_error('An error occurred. Please try again');
    }

    json_success([
        'id' => $projectId,
        'title' => $title,
        'description' => $description,
        'start_date' => DateTime::createFromFormat('Y-m-d', $startDate)->format('j F Y'),
        'end_date' => DateTime::createFromFormat('Y-m-d', $endDate)->format('j F Y'),
        'status' => $statusFormatted,
        'team_name' => $team_name,
    ], 'Project added successfully');
