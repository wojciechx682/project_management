<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    header('Content-Type: application/json; charset=UTF-8');

    $teamId = filter_var($_SESSION["selected_team_id"], FILTER_VALIDATE_INT);

    if (!$teamId) {
        json_error('No team selected');
    }

    $users = query(
        "SELECT u.id, u.first_name, u.last_name, u.email
         FROM user u
         WHERE u.role = 'Team Member'
           AND u.is_approved = 1
           AND u.id NOT IN (
               SELECT tu.user_id
               FROM team_user tu
               WHERE tu.team_id = ?
           )
         ORDER BY u.created_at ASC",
        "getAvailableUsersForTeam",
        [$teamId]
    );

    json_success(['users' => $users ?: []]);
