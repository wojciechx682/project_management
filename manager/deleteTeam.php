<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        json_error('Invalid request method', 405);
    }

    $teamId = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

    if (!$teamId) {
        json_error('Invalid team ID');
    }

    $success = query("DELETE FROM team WHERE id = ?", "deleteTeam", $teamId);

    if ($success) {
        json_success([], 'Team deleted successfully');
    }

    json_error('Team not found or deletion failed');
