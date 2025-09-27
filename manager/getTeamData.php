<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }

    if(isset($_GET["id"])) {

        $teamId = filter_var($_GET["id"], FILTER_VALIDATE_INT);

        if($teamId === false) {
            echo json_encode(["success" => false, "message" => "Invalid team ID"]);
            exit();
        }

        // Pobierz dane projektu z bazy danych
        $result = query("SELECT team.id, team.name FROM team WHERE team.id = ?", "getTeamForEdit", $teamId);

        //var_dump($result);

        if($result) {

            $team = $result->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                "success" => true,
                "team" => [
                    "id" => $team["id"],
                    "name" => $team["name"]
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Team not found"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Team ID not provided"]);
    }