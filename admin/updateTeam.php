<?php
    require_once "../start-session.php";
    require_role("Admin");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_error('Invalid request method', 405);
    }

    if (!isset($_POST["team_id"]) || empty($_POST["team_id"]) ||
        !isset($_POST["team_name"]) || empty($_POST["team_name"])) {
        json_error('All fields are required');
    }

    $id = filter_var($_POST["team_id"], FILTER_VALIDATE_INT);
    $teamName = htmlspecialchars(trim($_POST["team_name"]), ENT_QUOTES, 'UTF-8');

    if (!$id || !$teamName || strlen($teamName) > 255) {
        json_error('Invalid team data');
    }

    $teamExists = query("SELECT id FROM team WHERE name=? AND id != ? LIMIT 1", "checkIfTeamNameExists", [$teamName, $id]);
    if ($teamExists) {
        json_error('Team name already exists');
    }

    require_once __DIR__ . "/../notification_service.php";
    $oldTeam = query("SELECT name FROM team WHERE id = ?", "fetchOneAssoc", [$id]);

    $updateSuccessful = query("UPDATE team SET name=? WHERE id=?", "", [$teamName, $id]);

    if (!$updateSuccessful) {
        json_error('Failed to update team');
    }

    if ($oldTeam && $oldTeam["name"] !== $teamName) {
        notification_team_renamed($id, $oldTeam["name"], $teamName);
    }

    json_success([], 'Team updated successfully');
