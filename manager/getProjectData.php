<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    header('Content-Type: application/json; charset=UTF-8');

    if (!isset($_GET["id"])) {
        json_error('Project ID not provided');
    }

    $projectId = filter_var($_GET["id"], FILTER_VALIDATE_INT);

    if ($projectId === false) {
        json_error('Invalid project ID');
    }

    $result = query(
        "SELECT project.*, team.name AS team_name FROM project JOIN team ON project.team_id = team.id WHERE project.id=?",
        "getProjectForEdit",
        $projectId
    );

    if (!$result) {
        json_error('Project not found');
    }

    $project = $result->fetch(PDO::FETCH_ASSOC);
    $status = strtolower(str_replace(' ', '_', $project["status"]));

    json_success([
        'project' => [
            'id' => $project["id"],
            'name' => $project["name"],
            'description' => $project["description"],
            'status' => $status,
            'start_date' => $project["start_date"],
            'end_date' => $project["end_date"],
            'team_id' => $project["team_id"],
            'team_name' => $project["team_name"],
        ],
    ]);
