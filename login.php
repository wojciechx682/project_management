<?php

    require_once "start-session.php";

    if (!isset($_POST["email"]) || !isset($_POST["password"])) {
        header('Location: index.php');
        exit();
    } else {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $captchaToken = $_POST["g-recaptcha-response"];

            if (!verifyRecaptcha($captchaToken)) {
                $_SESSION["invalid_credentials"] = '<span class="error">reCaptcha verification failed</span>';
                header('Location: index.php');
                exit();
            }

            if (isset($_POST["email"]) && isset($_POST["password"])) {

                $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

                if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email) || ($email !== $_POST["email"])) {
                    $_SESSION["invalid_credentials"] = '<span class="error">Please provide a valid email address</span>';
                    header('Location: index.php');
                    exit();
                } else {
                    $_SESSION["invalid_credentials"] = '<span class="error">Incorrect email or password</span>'; // if user provided correct email and password, function "logIn" will remove that session variable
                    query("SELECT user.id, user.first_name, user.last_name, user.email, user.password, user.role, user.created_at, user.updated_at, user.is_approved FROM user WHERE user.email=?", "logIn", $email);
                    // if the function "logIn" didn't redirect user to right main page, this code below will continue to execute. Otherwise, it meant that user provided wrong email or password
                    if (isset($_SESSION["invalid_credentials"])) {
                        header('Location: index.php');
                        exit();
                    }
                }

            } else {
                $_SESSION["invalid_credentials"] = '<span class="error">Incorrect email or password</span>';
                header('Location: index.php');
                exit();
            }
        }
    }

