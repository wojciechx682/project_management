<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $response = ["success" => false];

        if (isset($_POST["team_name"]) && !empty($_POST["team_name"]) &&
            isset($_POST["user_id"]) && !empty($_POST["user_id"])) {

            // Pobranie danych + walidacja
            $teamName = htmlspecialchars($_POST["team_name"], ENT_QUOTES, "UTF-8");
            $pmId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

            if ($teamName !== $_POST["team_name"] || strlen($teamName) > 255 ||
                $pmId != $_POST["user_id"] || !$pmId) {
                $response["error"] = "An error occurred. Please provide valid team name or valid Project Manager";
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

                // $pmId   = $_SESSION["id"]; // ID zalogowanego Project Managera - Zmiana dla Admina. Teraz PM nie pochodzi z sesji tylko z formularza !

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
