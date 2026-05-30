<?php

    require_once "../start-session.php";

    require_role("Admin");

    header('Content-Type: application/json; charset=UTF-8');
    $updateSuccessful = false;

    if (isset($_POST["user-id"], $_POST["action"])) {

        $userId = filter_var($_POST["user-id"], FILTER_SANITIZE_NUMBER_INT);
        $action = filter_var($_POST["action"], FILTER_SANITIZE_STRING);

        if ($userId && in_array($action, ["accept", "reject"])) {

            if ($action === "accept") {
                $updateSuccessful = query("UPDATE user SET is_approved=? WHERE id=?","", [1, $userId]);
            } elseif ($action === "reject") {
                $updateSuccessful = query("DELETE FROM user WHERE id=?","", [$userId]);
            }
        }
    }

    if ($updateSuccessful === true) {
        json_success([], "Użytkownik został " . ($action === "accept" ? "zaakceptowany" : "odrzucony i usunięty"));
    } else {
        json_error("Wystąpił problem. Nie udało się zaktualizować użytkownika.");
    }
