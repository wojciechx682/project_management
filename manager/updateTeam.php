<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }

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
            if ($teamExists === true) {
                $response["success"] = false;
                $response["message"] = "Team name already exists";
                echo json_encode($response);
                exit();
            }

            // Aktualizacja zespołu w bazie danych
            $updateSuccessful = query("UPDATE team SET name=? WHERE id=?","", [$teamName, $id]);

            if ($updateSuccessful) {
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
