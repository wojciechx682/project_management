<?php
    require_once "../start-session.php";

    require_role("Admin");

    header('Content-Type: application/json; charset=UTF-8');

    //$teamId = filter_var($_SESSION["selected_team_id"], FILTER_VALIDATE_INT);

    /*if (!$teamId) {
        echo json_encode(["success" => false, "message" => "No team selected"]);
        exit();
    }*/

    $users = query(
        "SELECT u.id, u.first_name, u.last_name, u.email
         FROM user u
         WHERE u.role = 'Project Manager'           
         ORDER BY u.created_at ASC",
        "getAvailableUsersForTeam",
        []
    );

    if ($users && count($users) > 0) {
        json_success(["users" => $users]);
    } else {
        json_error("No available users", 404);
    }
