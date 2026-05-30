<?php
    require_once "../start-session.php";
    require_role("Admin");

    header('Content-Type: application/json; charset=UTF-8');

    $users = query(
        "SELECT u.id, u.first_name, u.last_name, u.email
         FROM user u
         WHERE u.role = 'Project Manager'
         ORDER BY u.created_at ASC",
        "getAvailableUsersForTeam",
        []
    );

    json_success(['users' => $users ?: []]);
