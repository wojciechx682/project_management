<?php

    require_once "start-session.php";

    if (!isset($_POST["email"]) || !isset($_POST["password"])) {
        header('Location: index.php');
            exit();
    } else {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            if (isset($_POST["email"]) && isset($_POST["password"])) {

                $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

                if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email) || ($email !== $_POST["email"])) {
                    $_SESSION["invalid_credentials"] = '<span class="error">Please provide a valid email address</span>';
                        header('Location: index.php');
                            exit();
                } else {
                    $_SESSION["invalid_credentials"] = '<span class="error">Incorrect email or password</span>';
                    query("SELECT user.id, user.first_name, user.last_name, user.email, user.password, user.role, user.created_at, user.updated_at, user.is_approved FROM user WHERE user.email=?", "log_in", $email);
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

