<?php
    require_once "../start-session.php";
    require_role("Admin");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        json_error('Invalid request method', 405);
    }

    $userId = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;
    $teamId = $_SESSION["selected_team_id"] ?? null;

    if (!$userId || !$teamId) {
        json_error('Invalid user or team ID');
    }

    $user = query(
        "SELECT role FROM user u 
         JOIN team_user tu ON u.id = tu.user_id 
         WHERE u.id = ? AND tu.team_id = ? 
         LIMIT 1",
        "getUserRoleForDelete",
        [$userId, $teamId]
    );

    if ($user && $user["role"] === "Project Manager") {
        json_error('You cannot remove the Project Manager from the team');
    }

    $success = query(
        "DELETE FROM team_user WHERE team_id = ? AND user_id = ?",
        "deleteUser",
        [$teamId, $userId]
    );

    if ($success) {
        json_success([], 'User removed from team successfully');
    }

    json_error('User not found in this team or deletion failed');
