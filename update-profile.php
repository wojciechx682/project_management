<?php

    require_once "start-session.php";

    if (isset($_SESSION["id"])) {

        if (isset($_POST["firstName"]) && !empty($_POST["firstName"]) &&
            isset($_POST["lastName"]) && !empty($_POST["lastName"]) &&
            isset($_POST["email"]) && !empty($_POST["email"])) {

            $firstName = trim($_POST["firstName"]);
            $lastName = trim($_POST["lastName"]);
            $email = trim($_POST["email"]);

            $_SESSION["valid"] = true; // flaga walidacji

            $maxStringLength = 255;

            // Walidacja imienia
            $firstName = ucfirst(strtolower(trim($firstName, " ")));
            $nameRegex = '/^[A-ZŁŚŻ]{1}[a-ząęółśżźćń]+$/u';
            if (!preg_match($nameRegex, $firstName)) {
                $_SESSION["valid"] = false;
                $_SESSION["profile-error"] = "First name can only contain letters and must start with a capital letter";
            }
            if (strlen($firstName) < 3 || strlen($firstName) > 27) {
                $_SESSION["valid"] = false;
                $_SESSION["profile-error"] = "Please provide a correct first name";
            }

            // Walidacja nazwiska
            $lastName = ucfirst(strtolower(trim($lastName, " ")));
            if (!preg_match($nameRegex, $lastName)) {
                $_SESSION["valid"] = false;
                $_SESSION["profile-error"] = "Last name can only contain letters and must start with a capital letter";
            }
            if (strlen($lastName) < 3 || strlen($lastName) > $maxStringLength) {
                $_SESSION["valid"] = false;
                $_SESSION["profile-error"] = "Please provide a correct last name";
            }

            // Walidacja e-maila
            $email = str_replace(str_split(" "), "", $email);
            $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (!filter_var($emailSanitized, FILTER_VALIDATE_EMAIL) || ($emailSanitized !== $email)) {
                $_SESSION["valid"] = false;
                $_SESSION["profile-error"] = "Please provide a valid email address";
            }

            // Sprawdź, czy email nie jest zajęty przez innego użytkownika
            $emailExists = query("SELECT user.id FROM user WHERE email=? AND id<>?","verifyEmailExists", [$emailSanitized, $_SESSION["id"]]); // id<>? - Excludes the current user (whose data you're editing) from the results. This prevents a false conflict if a user edits their profile and leaves their email address unchanged.

            if ($emailExists) {
                $_SESSION["valid"] = false;
                $_SESSION["profile-error"] = "There is already an account assigned to this email address";
            }

            if ($_SESSION["valid"]) {

                $user = [$firstName, $lastName, $emailSanitized, $_SESSION["id"]];

                $updateSuccessful = query("UPDATE user SET first_name=?, last_name=?, email=?, updated_at=NOW() WHERE id=?","updateProfile", $user);

                if ($updateSuccessful) {
                    // zaktualizuj sesję
                    $_SESSION["first_name"] = $firstName;
                    $_SESSION["last_name"] = $lastName;
                    $_SESSION["email"] = $emailSanitized;

                    $_SESSION["profile-successful"] = "Profile has been updated successfully";
                    header('Location: manager/profile.php');
                    exit();
                } else {
                    $_SESSION["profile-error"] = "An error occurred. Could not update profile";
                    header('Location: manager/profile.php');
                    exit();
                }
            } else {
                header('Location: manager/profile.php');
                exit();
            }
        } else {
            $_SESSION["profile-error"] = "All fields are required";
            header('Location: manager/profile.php');
            exit();
        }

    } else {
        header('Location: index.php');
        exit();
    }
