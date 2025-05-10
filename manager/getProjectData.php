<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }

    if(isset($_GET["id"])) {

        $projectId = filter_var($_GET["id"], FILTER_VALIDATE_INT);

        if($projectId === false) {
            echo json_encode(["success" => false, "message" => "Invalid project ID"]);
                exit();
        }

        // === WERYFIKACJA UPRAWNIEŃ DO ODZCZYTU DANYCH PROJEKTU ===
        $result = query("
                        SELECT p.id
                        FROM user u
                        JOIN team_user tu ON u.id = tu.user_id
                        JOIN team t ON tu.team_id = t.id
                        JOIN project p ON t.id = p.team_id
                        WHERE u.id = ?  
                        AND p.id = ? 
                        AND u.role = 'Project Manager'
                    ",
            "canUserViewProject",
            [$_SESSION["id"], $projectId]);

        if (!$result) {
            echo json_encode(["success" => false, "message" => "Access Denied: You do not have permission to view this project's data."]);
                exit();
        }

        // Pobierz dane projektu z bazy danych
        $result = query("SELECT project.*, team.name AS team_name FROM project JOIN team ON project.team_id = team.id WHERE project.id=?", "getProjectForEdit", $projectId);

        //var_dump($result);

        if($result) {

            $project = $result->fetch(PDO::FETCH_ASSOC);

            // Konwersja statusu do formatu formularza
            $status = strtolower(str_replace(' ', '_', $project["status"]));

            echo json_encode([
                "success" => true,
                "project" => [
                    "id" => $project["id"],
                    "name" => $project["name"],
                    "description" => $project["description"],
                    "status" => $status,
                    "start_date" => $project["start_date"],
                    "end_date" => $project["end_date"],
                    "team_id" => $project["team_id"],
                    "team_name" => $project["team_name"]
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Project not found"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Project ID not provided"]);
    }