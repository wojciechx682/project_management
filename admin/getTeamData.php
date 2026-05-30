<?php
    require_once "../start-session.php";
    require_role("Admin");

    header('Content-Type: application/json; charset=UTF-8');

    if (!isset($_GET["id"])) {
        json_error('Team ID not provided');
    }

    $teamId = filter_var($_GET["id"], FILTER_VALIDATE_INT);

    if ($teamId === false) {
        json_error('Invalid team ID');
    }

    $result = query("SELECT team.id, team.name FROM team WHERE team.id = ?", "getTeamForEdit", $teamId);

    if (!$result) {
        json_error('Team not found');
    }

    $team = $result->fetch(PDO::FETCH_ASSOC);

    json_success([
        'team' => [
            'id' => $team["id"],
            'name' => $team["name"],
        ],
    ]);
