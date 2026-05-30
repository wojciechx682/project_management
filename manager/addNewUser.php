<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $response = ["success" => false];

        if (isset($_POST["user_id"]) && !empty($_POST["user_id"]) &&
            isset($_SESSION["selected_team_id"]) && !empty($_SESSION["selected_team_id"])) {

            // Pobranie danych + walidacja
            $userId = filter_var($_POST["user_id"], FILTER_VALIDATE_INT);
            $teamId = filter_var($_SESSION["selected_team_id"], FILTER_VALIDATE_INT);

            if (!$userId || !$teamId) {
                $response["message"] = "Invalid user or team ID";
                echo json_encode($response);
                exit();
            }

            // Sprawdź, czy użytkownik istnieje i spełnia warunki (is_approved=1, role=Team Member)
            $user = query(
                "SELECT id, first_name, last_name, email, role, created_at, updated_at 
                 FROM user 
                 WHERE id = ? AND is_approved = 1 AND role = 'Team Member' 
                 LIMIT 1",
                "getUserForTeam",
                [$userId]
            );

            if (!$user) {
                $response["message"] = "User not eligible to join team";
                echo json_encode($response);
                exit();
            }

            // Sprawdź, czy nie jest już w tym zespole
            $exists = query(
                "SELECT 1 FROM team_user WHERE team_id = ? AND user_id = ? LIMIT 1",
                "checkIfUserInTeam",
                [$teamId, $userId]
            );

            if ($exists) {
                $response["message"] = "User is already a member of this team";
                echo json_encode($response);
                exit();
            }

            // Dodanie do team_user
            $insertSuccessful = query(
                "INSERT INTO team_user (team_id, user_id) VALUES (?, ?)",
                "",
                [$teamId, $userId]
            );

            if ($insertSuccessful) {
                // notification - Dodanie nowego członka do zespołu (PM)
                require_once __DIR__ . "/../notification_service.php";
                $tname = query("SELECT name FROM team WHERE id = ?", "fetchOneAssoc", [$teamId]);
                if ($tname) {
                    notification_team_joined($userId, $tname["name"], $teamId);
                }
                // end notification
                $response["success"] = true;
                $response["message"] = "User added successfully";
                $response["user"] = [
                    "id" => $user["id"],
                    "first_name" => $user["first_name"],
                    "last_name" => $user["last_name"],
                    "email" => $user["email"],
                    "role" => $user["role"],
                    "created_at" => DateTime::createFromFormat('Y-m-d H:i:s', $user["created_at"])->format('j F Y, H:i'),
                    "updated_at" => DateTime::createFromFormat('Y-m-d H:i:s', $user["updated_at"])->format('j F Y, H:i')
                ];
            } else {
                $response["message"] = "Failed to add user to team";
            }
        } else {
            $response["message"] = "All fields are required";
        }

        echo json_encode($response);
    }
