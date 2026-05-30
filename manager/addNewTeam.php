<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $response = ["success" => false];

        if (isset($_POST["team_name"]) && !empty($_POST["team_name"])) {

            // Pobranie danych + walidacja
            $teamName = htmlspecialchars($_POST["team_name"], ENT_QUOTES, "UTF-8");

            if ($teamName !== $_POST["team_name"] || strlen($teamName) > 255) {
                json_error("An error occurred. Please provide valid team name");
            }

            $teamNameExists = query("SELECT team.name FROM team WHERE team.name = ? LIMIT 1","checkIfTeamNameExists", [$teamName]);
            if ($teamNameExists) {
                json_error("Team name already exists");
            }


            // Wstawienie nowego zespołu do bazy
            $teamData = [$teamName];
            $insertSuccessful = query("INSERT INTO team (id, name, created_at) VALUES (NULL, ?, NOW())","addNewTeam", $teamData);

            if ($insertSuccessful) {

                $teamId = $insertSuccessful;

                $pmId   = $_SESSION["id"]; // ID zalogowanego Project Managera
                // Dodanie wpisu do team_user: PM -> team
                $teamUserData = [$teamId, $pmId];
                $insertTeamUser = query("INSERT INTO team_user (team_id, user_id) VALUES (?, ?)",null, $teamUserData);

                if (!$insertTeamUser) {
                    json_error("Failed to link PM to team");
                }

                // Pobranie informacji o nowym zespole (np. liczby członków)
                $membersCount = query("SELECT COUNT(*) AS members_count FROM team_user WHERE team_id = ?","getTeamMembersCount", $teamId);

                if ($membersCount === null) {
                    $membersCount = 0;
                }

                json_success([
                    "team" => [
                        "id" => $teamId,
                        "team_name" => $teamName,
                        "created_at" => date("j F Y, H:i"),
                        "members_count" => $membersCount,
                    ],
                ], "Team added successfully");
            } else {
                json_error("Failed to insert team");
            }

        } else {
            json_error("Team name is required");
        }
    } else {
        json_error("Invalid request method", 405);
    }
