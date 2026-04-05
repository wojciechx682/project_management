<?php

declare(strict_types=1);

if (isset($_GET["id"])) {

    $projectId = filter_var($_GET["id"], FILTER_VALIDATE_INT);

    if ($projectId === false) {
        echo json_encode(["success" => false, "message" => "Invalid project ID"]);
        exit();
    }

    $result = query("SELECT project.*, team.name AS team_name FROM project JOIN team ON project.team_id = team.id WHERE project.id=?", "getProjectForEdit", $projectId);

    if ($result) {

        $project = $result->fetch(PDO::FETCH_ASSOC);

        $status = strtolower(str_replace(' ', '_', $project["status"]));

        echo json_encode([
            "success" => true,
            "project" => [
                "id" => $project["id"],
                "name" => $project["name"],
                "description" => $project["description"],
                "status" => $status,
                "start_date" => $project["start_date"],
                "end_date" => $project["end_date"],
                "team_id" => $project["team_id"],
                "team_name" => $project["team_name"]
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Project not found"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Project ID not provided"]);
}
