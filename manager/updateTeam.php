<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $response = ["success" => false];

        if (isset($_POST["team_id"]) && !empty($_POST["team_id"]) &&
            isset($_POST["team_name"]) && !empty($_POST["team_name"])) {

            // Walidacja danych
            $id = filter_var($_POST["team_id"], FILTER_VALIDATE_INT);
            $teamName = htmlspecialchars(trim($_POST["team_name"]), ENT_QUOTES, "UTF-8");

            if (!$id || !$teamName || strlen($teamName) > 255) {
                json_error("Invalid team data");
            }

            // Sprawdź, czy nazwa zespołu już istnieje
            $teamExists = query("SELECT id FROM team WHERE name=? AND id != ? LIMIT 1", "checkIfTeamNameExists", [$teamName, $id]);
            if ($teamExists) {
                json_error("Team name already exists");
            }

            // notification - Zmiana nazwy zespołu (dla członków zespołu)
            require_once __DIR__ . "/../notification_service.php";
            $oldTeam = query("SELECT name FROM team WHERE id = ?", "fetchOneAssoc", [$id]);
            // end notification

            // Aktualizacja zespołu w bazie danych
            $updateSuccessful = query("UPDATE team SET name=? WHERE id=?","", [$teamName, $id]);

            if ($updateSuccessful) {
                // notification - Zmiana nazwy zespołu (dla członków zespołu)
                if ($oldTeam && $oldTeam["name"] !== $teamName) {
                    notification_team_renamed($id, $oldTeam["name"], $teamName);
                }
                // end notification
                json_success([], "Team updated successfully");
            } else {
                json_error("Failed to update team");
            }
        } else {
            json_error("All fields are required");
        }
    } else {
        json_error("Invalid request method", 405);
    }
