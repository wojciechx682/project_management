<?php
    require_once "../start-session.php";

    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }

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
        echo json_encode(["success" => true, "users" => $users]);
    } else {
        echo json_encode(["success" => false, "message" => "No available users"]);
    }
