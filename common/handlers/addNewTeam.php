<?php

declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    return;
}

$response = ["success" => false];
$role = $_SESSION['role'];

if ($role === 'Admin') {

    if (isset($_POST["team_name"]) && !empty($_POST["team_name"]) &&
        isset($_POST["user_id"]) && !empty($_POST["user_id"])) {

        $teamName = htmlspecialchars($_POST["team_name"], ENT_QUOTES, "UTF-8");
        $pmId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

        if ($teamName !== $_POST["team_name"] || strlen($teamName) > 255 ||
            $pmId != $_POST["user_id"] || !$pmId) {
            $response["error"] = "An error occurred. Please provide valid team name or valid Project Manager";
            echo json_encode($response);
            exit();
        }

        $teamNameExists = query("SELECT team.name FROM team WHERE team.name = ? LIMIT 1", "checkIfTeamNameExists", [$teamName]);
        if ($teamNameExists) {
            echo json_encode(["success" => false, "message" => "Team name already exists"]);
            exit();
        }

        $teamData = [$teamName];
        $insertSuccessful = query("INSERT INTO team (id, name, created_at) VALUES (NULL, ?, NOW())", "addNewTeam", $teamData);

        if ($insertSuccessful) {

            $teamId = $insertSuccessful;
            $teamUserData = [$teamId, $pmId];
            $insertTeamUser = query("INSERT INTO team_user (team_id, user_id) VALUES (?, ?)", null, $teamUserData);

            if (!$insertTeamUser) {
                echo json_encode(["success" => false, "message" => "Failed to link PM to team"]);
                exit();
            }

            $membersCount = query("SELECT COUNT(*) AS members_count FROM team_user WHERE team_id = ?", "getTeamMembersCount", $teamId);

            if ($membersCount === null) {
                $membersCount = 0;
            }

            echo json_encode([
                "success" => true,
                "id" => $teamId,
                "team_name" => $teamName,
                "created_at" => date("j F Y, H:i"),
                "members_count" => $membersCount
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to insert team"]);
            exit();
        }

    } else {
        echo json_encode(["success" => false, "message" => "Team name is required"]);
        exit();
    }

    return;
}

// Project Manager — PM z sesji, bez wyboru w formularzu
if (isset($_POST["team_name"]) && !empty($_POST["team_name"])) {

    $teamName = htmlspecialchars($_POST["team_name"], ENT_QUOTES, "UTF-8");

    if ($teamName !== $_POST["team_name"] || strlen($teamName) > 255) {
        $response["error"] = "An error occurred. Please provide valid team name";
        echo json_encode($response);
        exit();
    }

    $teamNameExists = query("SELECT team.name FROM team WHERE team.name = ? LIMIT 1", "checkIfTeamNameExists", [$teamName]);
    if ($teamNameExists) {
        echo json_encode(["success" => false, "message" => "Team name already exists"]);
        exit();
    }

    $teamData = [$teamName];
    $insertSuccessful = query("INSERT INTO team (id, name, created_at) VALUES (NULL, ?, NOW())", "addNewTeam", $teamData);

    if ($insertSuccessful) {

        $teamId = $insertSuccessful;

        $pmId = $_SESSION["id"];
        $teamUserData = [$teamId, $pmId];
        $insertTeamUser = query("INSERT INTO team_user (team_id, user_id) VALUES (?, ?)", null, $teamUserData);

        if (!$insertTeamUser) {
            echo json_encode(["success" => false, "message" => "Failed to link PM to team"]);
            exit();
        }

        $membersCount = query("SELECT COUNT(*) AS members_count FROM team_user WHERE team_id = ?", "getTeamMembersCount", $teamId);

        if ($membersCount === null) {
            $membersCount = 0;
        }

        echo json_encode([
            "success" => true,
            "id" => $teamId,
            "team_name" => $teamName,
            "created_at" => date("j F Y, H:i"),
            "members_count" => $membersCount
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to insert team"]);
        exit();
    }

} else {
    echo json_encode(["success" => false, "message" => "Team name is required"]);
    exit();
}
