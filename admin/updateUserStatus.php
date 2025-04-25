<?php

    require_once "../start-session.php";

    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }

    header('Content-Type: application/json');
    $response = [];
    $updateSuccessful = false;

    if (isset($_POST["user-id"], $_POST["action"])) {

        $userId = filter_var($_POST["user-id"], FILTER_SANITIZE_NUMBER_INT);
        $action = filter_var($_POST["action"], FILTER_SANITIZE_STRING);

        if ($userId && in_array($action, ["accept", "reject"])) {

            if ($action === "accept") {
                $updateSuccessful = query(
                    "UPDATE user SET is_approved='%s' WHERE id = '%s'",
                    "",
                    [1, $userId]
                );
            } elseif ($action === "reject") {
                $updateSuccessful = query(
                    "DELETE FROM user WHERE id = '%s'",
                    "",
                    [$userId]
                );
            }
        }
    }

    if ($updateSuccessful === true) {
        $response["success"] = true;
        $response["message"] = "Użytkownik został " . ($action === "accept" ? "zaakceptowany" : "odrzucony i usunięty");
    } else {
        $response["success"] = false;
        $response["message"] = "Wystąpił problem. Nie udało się zaktualizować użytkownika.";
    }

    echo json_encode($response);
