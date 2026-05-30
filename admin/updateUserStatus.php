<?php
    require_once "../start-session.php";
    require_role("Admin");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_error('Invalid request method', 405);
    }

    if (!isset($_POST["user-id"], $_POST["action"])) {
        json_error('Wystąpił problem. Nie udało się zaktualizować użytkownika.');
    }

    $userId = filter_var($_POST["user-id"], FILTER_SANITIZE_NUMBER_INT);
    $action = filter_var($_POST["action"], FILTER_SANITIZE_STRING);

    if (!$userId || !in_array($action, ["accept", "reject"])) {
        json_error('Wystąpił problem. Nie udało się zaktualizować użytkownika.');
    }

    if ($action === "accept") {
        $updateSuccessful = query("UPDATE user SET is_approved=? WHERE id=?", "", [1, $userId]);
    } else {
        $updateSuccessful = query("DELETE FROM user WHERE id=?", "", [$userId]);
    }

    if (!$updateSuccessful) {
        json_error('Wystąpił problem. Nie udało się zaktualizować użytkownika.');
    }

    $message = $action === "accept"
        ? "Użytkownik został zaakceptowany"
        : "Użytkownik został odrzucony i usunięty";

    json_success([], $message);
