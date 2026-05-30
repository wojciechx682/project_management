<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    header('Content-Type: application/json; charset=UTF-8');

    $response = ["success" => false, "message" => ""];

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $userId = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;
        $teamId = $_SESSION["selected_team_id"] ?? null;

        if (!$userId || !$teamId) {
            $response["message"] = "Invalid user or team ID";
            json_response($response);
        }

        // 🔹 Sprawdź rolę użytkownika
        $user = query(
            "SELECT role FROM user u 
                   JOIN team_user tu ON u.id = tu.user_id 
                   WHERE u.id = ? AND tu.team_id = ? 
                   LIMIT 1",
            "getUserRoleForDelete",
            [$userId, $teamId]
        );

        if ($user && $user["role"] === "Project Manager") {
            $response["message"] = "You cannot remove the Project Manager from the team";
            json_response($response);
        }

        // 🔹 Usuń tylko, jeśli to nie PM
        $success = query(
            "DELETE FROM team_user WHERE team_id = ? AND user_id = ?",
            "deleteUser",
            [$teamId, $userId]
        );

        if ($success) {
            $response["success"] = true;
            $response["message"] = "User removed from team successfully";
        } else {
            $response["message"] = "User not found in this team or deletion failed";
        }
    } else {
        $response["message"] = "Invalid request method";
    }
    json_response($response);
