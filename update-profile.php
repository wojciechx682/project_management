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

        if (isset($_POST["firstName"]) && !empty($_POST["firstName"]) &&
            isset($_POST["lastName"]) && !empty($_POST["lastName"]) &&
            isset($_POST["email"]) && !empty($_POST["email"])) {

            $firstName = trim($_POST["firstName"]);
            $lastName = trim($_POST["lastName"]);
            $email = trim($_POST["email"]);

            $valid = true;
            $maxStringLength = 255;

            // Walidacja imienia
            $firstName = ucfirst(strtolower(trim($firstName, " ")));
            $nameRegex = '/^[A-ZŁŚŻ]{1}[a-ząęółśżźćń]+$/u';
            if (!preg_match($nameRegex, $firstName)) {
                $valid = false;
                $response["message"] = "First name can only contain letters and must start with a capital letter";
            }
            if (strlen($firstName) < 3 || strlen($firstName) > 27) {
                $valid = false;
                $response["message"] = "Please provide a correct first name";
            }

            // Walidacja nazwiska
            $lastName = ucfirst(strtolower(trim($lastName, " ")));
            if (!preg_match($nameRegex, $lastName)) {
                $valid = false;
                $response["message"] = "Last name can only contain letters and must start with a capital letter";
            }
            if (strlen($lastName) < 3 || strlen($lastName) > $maxStringLength) {
                $valid = false;
                $response["message"] = "Please provide a correct last name";
            }

            // Walidacja e-maila
            $email = str_replace(str_split(" "), "", $email);
            $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (!filter_var($emailSanitized, FILTER_VALIDATE_EMAIL) || ($emailSanitized !== $email)) {
                $valid = false;
                $response["message"] = "Please provide a valid email address";
            }

            // Sprawdź, czy email nie jest zajęty przez innego użytkownika
            if ($valid) {
                $emailExists = query("SELECT user.id FROM user WHERE email=? AND id<>?","verifyEmailExists", [$emailSanitized, $_SESSION["id"]]);

                if ($emailExists) {
                    $valid = false;
                    $response["message"] = "There is already an account assigned to this email address";
                }
            }

            if ($valid) {
                $user = [$firstName, $lastName, $emailSanitized, $_SESSION["id"]];
                $updatedAt = query("UPDATE user SET first_name=?, last_name=?, email=?, updated_at=NOW() WHERE id=?","updateProfile", $user);

                if ($updatedAt) {
                    // Odśwież sesję
                    $_SESSION["first_name"] = $firstName;
                    $_SESSION["last_name"] = $lastName;
                    $_SESSION["email"] = $emailSanitized;
                    $_SESSION["updated_at"] = $updatedAt;

                    $response["success"] = true;
                    $response["message"] = "Profile has been updated successfully";
                    $response["updated_at"] = $updatedAt;
                } else {
                    $response["message"] = "An error occurred. Could not update profile";
                }
            }

        } else {
            $response["message"] = "All fields are required";
        }

    } else {
        $response["message"] = "Invalid request method";
    }

    echo json_encode($response);
