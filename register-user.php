<?php

    require_once "start-session.php";

    if (isset($_POST["firstName"]) && !empty($_POST["firstName"]) &&
        isset($_POST["lastName"]) && !empty($_POST["lastName"]) &&
        isset($_POST["email"]) && !empty($_POST["email"]) &&
        isset($_POST["password"]) && !empty($_POST["password"]) &&
        isset($_POST["confirm-password"]) && !empty($_POST["confirm-password"]) &&
        isset($_POST["role"]) && !empty($_POST["role"])) {

        $firstName = trim($_POST["firstName"]);
        $lastName = trim($_POST["lastName"]);
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirm-password"];
        $role = $_POST["role"];

        switch ($role) {
            case "team_member":
                $role = "Team Member";
                break;
            case "project_manager":
                $role = "Project Manager";
                break;
        }


        $_SESSION["valid"] = true; // validation flag;

        $maxStringLength = 255;

        $firstName = ucfirst(strtolower(trim($firstName, " ")));
        $nameRegex = '/^[A-ZŁŚŻ]{1}[a-ząęółśżźćń]+$/u';
        if (!preg_match($nameRegex, $firstName)) {
            $_SESSION["valid"] = false;
            $_SESSION["register-error"] = "The name can only consist of letters of the alphabet.";
        }
        if (strlen($firstName)<3 || strlen($firstName)>27) {
            $_SESSION["valid"] = false;
            $_SESSION["register-error"] = "Please provide the correct name";
        }

        $lastName = ucfirst(strtolower(trim($lastName, " ")));
        if (!preg_match($nameRegex, $lastName)) {
            $_SESSION["valid"] = false;
            $_SESSION["register-error"] = "The name can only consist of letters of the alphabet.";
        }
        if (strlen($lastName)<3 || strlen($lastName)>$maxStringLength) {
            $_SESSION["valid"] = false;
            $_SESSION["register-error"] = "Please provide the correct name";
        }

        $email = str_replace(str_split(" "), "", $email);
        $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($emailSanitized, FILTER_VALIDATE_EMAIL) || ($emailSanitized !== $email)) {
            $_SESSION["valid"] = false;
            $_SESSION["register-error"] = "Please provide a valid email address";
        }

        $passRegex = '/^((?=.*[!@#$%^&_*+-\/\?])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])).{10,31}$/';
        if (!preg_match($passRegex, $password)) {
            $_SESSION["valid"] = false;
            $_SESSION["register-error"] = "The password must be between 10 and 30 characters long, contain at least one uppercase letter, one lowercase letter, one number and one special character";
        }
        if($password !== $confirmPassword) {
            $_SESSION["valid"] = false;
            $_SESSION["register-error"] = "The passwords provided are different";
        }
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        query("SELECT user.id FROM user WHERE email='%s'", "registerVerifyEmail", $emailSanitized);
        // will set the $_SESSION["valid"] variable to false if such an email already exists (i.e. if it RETURNS records -> $result);

        if ($_SESSION["valid"]) {

            $user = [$firstName, $lastName, $email, $passwordHash, $role];

            $insertSuccessful = query("INSERT INTO user (id, first_name, last_name, email, password, role, created_at, updated_at, is_approved) VALUES (NULL, ?, ?, ?, ?, ?, NOW(), NOW(), 0)", "register", $user);

            if ($insertSuccessful) {
                header('Location: index.php'); // $_SESSION["register-successful"] = "...";
                    exit();
            } else { // failed to add user
                $_SESSION["register-error"] = "An error occurred. Could not add new user";
                    header('Location: register.php');
                        exit();
            }

        } else {
            header('Location: register.php');
                exit();
        }

    } else {
        header('Location: index.php');
            exit();
    }