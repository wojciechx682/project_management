<?php

    // API dla przeglądarki — user/get-notifications.php i user/mark-notification-read.php
    // Team Member pobiera listę i oznacza jako przeczytane.

    // Oznacza jedno powiadomienie jako przeczytane (POST JSON { id }).

    require_once "../start-session.php";

    header("Content-Type: application/json; charset=UTF-8");

    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Team Member") {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Forbidden"]);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        echo json_encode(["success" => false, "message" => "Method not allowed"]);
        exit();
    }

    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);
    $id = isset($data["id"]) ? filter_var($data["id"], FILTER_VALIDATE_INT) : null;

    if (!$id) {
        echo json_encode(["success" => false, "message" => "Invalid id"]);
        exit();
    }

    $userId = (int) $_SESSION["id"];

    $existing = query(
        "SELECT read_at FROM notification WHERE id = ? AND user_id = ? LIMIT 1",
        "fetchOneAssoc",
        [$id, $userId]
    );

    if (!$existing) {
        echo json_encode(["success" => false, "message" => "Not found"]);
        exit();
    }

    if ($existing["read_at"]) {
        echo json_encode(["success" => true]);
        exit();
    }

    $ok = query(
        "UPDATE notification SET read_at = NOW() WHERE id = ? AND user_id = ? AND read_at IS NULL",
        "updateTask",
        [$id, $userId]
    );

    if ($ok) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Could not update notification"]);
    }
