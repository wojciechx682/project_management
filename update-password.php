<?php
    require_once "start-session.php";

    header('Content-Type: application/json');

    $response = ["success" => false];

    if (!isset($_SESSION["id"])) {
        $response["message"] = "Unauthorized";
        echo json_encode($response);
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $currentPassword = $_POST["currentPassword"];
        $newPassword     = $_POST["newPassword"];
        $confirmPassword = $_POST["confirmPassword"];

        if (!$currentPassword || !$newPassword || !$confirmPassword) {
            $response["message"] = "All fields are required";
            echo json_encode($response);
            exit();
        }

        if ($newPassword !== $confirmPassword) {
            $response["message"] = "Passwords do not match";
            echo json_encode($response);
            exit();
        }

        if (strlen($newPassword) < 10) {
            $response["message"] = "Password must be at least 8 characters long";
            echo json_encode($response);
            exit();
        }

        $passRegex = '/^((?=.*[!@#$%^&_*+-\/\?])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])).{10,31}$/';
        if (!preg_match($passRegex, $newPassword)) {
            $response["message"] = "The password must be between 10 and 30 characters long, contain at least one uppercase letter, one lowercase letter, one number and one special character";
            echo json_encode($response);
            exit();
        }

        // Pobierz hash hasła użytkownika
        $userPassword = query("SELECT password FROM user WHERE id=?", "fetchPasswordHash", $_SESSION["id"]);

        if (!$userPassword || !password_verify($currentPassword, $userPassword)) {
            $response["message"] = "Current password is incorrect";
            echo json_encode($response);
            exit();
        }

        // Zaktualizuj nowe hasło
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateSuccessful = query("UPDATE user SET password=?, updated_at=NOW() WHERE id=?", "", [$newHash, $_SESSION["id"]]);

        if ($updateSuccessful) {
            $response["success"] = true;
            $response["message"] = "Password has been updated successfully";
        } else {
            $response["message"] = "Failed to update password";
        }
    } else {
        $response["message"] = "Invalid request method";
    }

    echo json_encode($response);
