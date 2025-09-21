<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $response = ["success" => false];

        if (isset($_POST["team_name"]) && !empty($_POST["team_name"])) {

            // Pobranie danych + walidacja
            $teamName = htmlspecialchars($_POST["team_name"], ENT_QUOTES, "UTF-8");

            if ($teamName !== $_POST["team_name"] || strlen($teamName) > 255) {
                $response["error"] = "An error occurred. Please provide valid team name";
                echo json_encode($response);
                exit();
            }

            $teamNameExists = query("SELECT team.name FROM team WHERE team.name = ? LIMIT 1","checkIfTeamNameExists", [$teamName]);
            if ($teamNameExists) {
                echo json_encode(["success" => false, "message" => "Team name already exists"]);
                exit();
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
                    echo json_encode(["success" => false, "message" => "Failed to link PM to team"]);
                    exit();
                }

                // Pobranie informacji o nowym zespole (np. liczby członków)
                $membersCount = query("SELECT COUNT(*) AS members_count FROM team_user WHERE team_id = ?","getTeamMembersCount", $teamId);

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
    }
