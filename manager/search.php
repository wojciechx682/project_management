<?php
    require_once "../start-session.php";

    if (!isset($_SESSION["role"])) {
        echo json_encode(["projects" => [], "teams" => [], "users" => []]);
        exit();
    }

    header("Content-Type: application/json");

    $query = isset($_GET["query"]) ? trim($_GET["query"]) : "";

    if (strlen($query) < 2) {
        echo json_encode(["projects" => [], "teams" => [], "users" => []]);
        exit();
    }

    // Szukamy w trzech kategoriach
    $projects = query(
        "SELECT id, name FROM project WHERE name LIKE ? ORDER BY created_at DESC LIMIT 10",
        "fetchAllAssoc",
        ["%$query%"]
    );

    $teams = query(
        "SELECT id, name FROM team WHERE name LIKE ? ORDER BY created_at DESC LIMIT 10",
        "fetchAllAssoc",
        ["%$query%"]
    );

    $users = query(
        "SELECT id, first_name, last_name FROM user 
         WHERE (first_name LIKE ? OR last_name LIKE ?) 
         AND is_approved = 1
         ORDER BY created_at DESC LIMIT 10",
        "fetchAllAssoc",
        ["%$query%", "%$query%"]
    );

    echo json_encode([
        "projects" => $projects ?: [],
        "teams" => $teams ?: [],
        "users" => $users ?: []
    ]);
