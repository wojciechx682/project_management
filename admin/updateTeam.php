<?php
    require_once "../start-session.php";
    require_role("Admin");

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $response = ["success" => false];

        if (isset($_POST["team_id"]) && !empty($_POST["team_id"]) &&
            isset($_POST["team_name"]) && !empty($_POST["team_name"])) {

            // Walidacja danych
            $id = filter_var($_POST["team_id"], FILTER_VALIDATE_INT);
            $teamName = htmlspecialchars(trim($_POST["team_name"]), ENT_QUOTES, "UTF-8");

            if (!$id || !$teamName || strlen($teamName) > 255) {
                $response["message"] = "Invalid team data";
                echo json_encode($response);
                exit();
            }

            // Sprawdź, czy nazwa zespołu już istnieje
            $teamExists = query("SELECT id FROM team WHERE name=? AND id != ? LIMIT 1", "checkIfTeamNameExists", [$teamName, $id]);
            if ($teamExists) {
                $response["success"] = false;
                $response["message"] = "Team name already exists";
                echo json_encode($response);
                exit();
            }

            require_once __DIR__ . "/../notification_service.php";
            $oldTeam = query("SELECT name FROM team WHERE id = ?", "fetchOneAssoc", [$id]);

            // Aktualizacja zespołu w bazie danych
            $updateSuccessful = query("UPDATE team SET name=? WHERE id=?","", [$teamName, $id]);

            if ($updateSuccessful) {
                if ($oldTeam && $oldTeam["name"] !== $teamName) {
                    notification_team_renamed($id, $oldTeam["name"], $teamName);
                }
                $response["success"] = true;
                $response["message"] = "Team updated successfully";
            } else {
                $response["message"] = "Failed to update team";
            }
        } else {
            $response["message"] = "All fields are required";
        }

        echo json_encode($response);
    }
