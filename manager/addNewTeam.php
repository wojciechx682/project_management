<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_error('Invalid request method', 405);
    }

    if (!isset($_POST["team_name"]) || empty($_POST["team_name"])) {
        json_error('Team name is required');
    }

    $teamName = htmlspecialchars($_POST["team_name"], ENT_QUOTES, 'UTF-8');

    if ($teamName !== $_POST["team_name"] || strlen($teamName) > 255) {
        json_error('An error occurred. Please provide valid team name');
    }

    $teamNameExists = query("SELECT team.name FROM team WHERE team.name = ? LIMIT 1", "checkIfTeamNameExists", [$teamName]);
    if ($teamNameExists) {
        json_error('Team name already exists');
    }

    $insertSuccessful = query("INSERT INTO team (id, name, created_at) VALUES (NULL, ?, NOW())", "addNewTeam", [$teamName]);

    if (!$insertSuccessful) {
        json_error('Failed to insert team');
    }

    $teamId = $insertSuccessful;
    $pmId = $_SESSION["id"];
    $insertTeamUser = query("INSERT INTO team_user (team_id, user_id) VALUES (?, ?)", null, [$teamId, $pmId]);

    if (!$insertTeamUser) {
        json_error('Failed to link PM to team');
    }

    $membersCount = query("SELECT COUNT(*) AS members_count FROM team_user WHERE team_id = ?", "getTeamMembersCount", $teamId);
    if ($membersCount === null) {
        $membersCount = 0;
    }

    json_success([
        'id' => $teamId,
        'team_name' => $teamName,
        'created_at' => date("j F Y, H:i"),
        'members_count' => $membersCount,
    ], 'Team added successfully');
