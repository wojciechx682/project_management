<?php

    function log_in($result) {

        unset($_SESSION["invalid_credentials"]);

        $row = $result->fetch_assoc();

        $password = $_POST["password"];

        if (password_verify($password, $row["password"]) && $row["is_approved"]) {

            $_SESSION["logged-in"] = true;
            $_SESSION["id"] = $row["id"];
            $_SESSION["first_name"] = $row["first_name"];
            $_SESSION["last_name"] = $row["last_name"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["role"] = $row["role"];
            $_SESSION["created_at"] = $row["created_at"];
            $_SESSION["updated_at"] = $row["updated_at"];

            switch ($_SESSION["role"]) {
                case "Admin":
                    header("Location: admin/admin.php");
                    break;
                case "Project Manager":
                    header("Location: manager/manager.php");
                    break;
                case "Team Member":
                    header("Location: user/user.php");
                    break;
                default:
                    $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned.</span>';
                    header("Location: index.php");
                    break;
            }

        } elseif (!$row["is_approved"]) {
            $_SESSION["invalid_credentials"] = '<span class="error">Your account is awaiting administrator approval</span>';
                header('Location: index.php');
                    exit();
        } else {
            $_SESSION["invalid_credentials"] = '<span class="error">Incorrect email or password</span>';
            header('Location: index.php');
            exit();
        }
    }

    function getAllProjects($result) {
        require "../view/manager/project-header.php"; // table header;
        $project = file_get_contents("../template/project.php");
        while ($row = $result->fetch_assoc()) {
            echo sprintf($project, $row["id"], $row["id"], $row["name"], $row["description"], $row["start_date"], $row["end_date"], $row["status"], $row["team_name"]);
        }
    }

    function getProjectDetails($result) {
        //require "../view/manager/project-header.php"; // table header;
        $projectDetails = file_get_contents("../template/project-details.php");
        $row = $result->fetch_assoc();
        echo sprintf($projectDetails, $row["id"], $row["name"], $row["description"], $row["start_date"], $row["end_date"], $row["status"], $row["created_at"], $row["updated_at"], $row["team_name"]);
    }

    function getTaskDetails($result) {
        require "../view/manager/task-header.php"; // table header;
        $taskDetails = file_get_contents("../template/task-details.php");
        while ($row = $result->fetch_assoc()) {
            echo sprintf($taskDetails, $row["id"], $row["id"], $row["title"], $row["priority"], $row["status"], $row["due_date"], $row["first_name"], $row["last_name"], $row["created_at"], $row["updated_at"]);
        }
    }

    function getTasks($result) {
        //require "task-details-window.php";
        $taskDetails = file_get_contents("../template/task-details-window.php");
        $row = $result->fetch_assoc();
        echo sprintf($taskDetails, $row["id"], $row["title"], $row["description"], $row["priority"], $row["status"], $row["due_date"], $row["name"], $row["first_name"], $row["last_name"], $row["created_at"], $row["updated_at"]);
    }

    function registerVerifyEmail($result) {
        $_SESSION["valid"] = false;
        $_SESSION["register-error"] = "There is already an account assigned to this email address";
    }

    function register($connection) {
        $_SESSION["register-successful"] = "The new account has been successfully created and is waiting for approval from the System Administrator";
        return true;
    }

    function getPendingUsers($result) {
        require "../view/admin/users-header.php"; // table header;
        $users = file_get_contents("../template/users.php");
        while ($row = $result->fetch_assoc()) {
            echo sprintf($users, $row["id"], $row["first_name"], $row["last_name"], $row["email"], $row["role"], $row["created_at"], $row["is_approved"], $row["id"], $row["id"]);
        }
    }


    function query($query, $fun, $values) {

        // $query - SQL - "SELECT imie, nazwisko FROM klienci";

        // $fun   - callback function
        // - wywołaj funkcję tylko wtedy, jeśli $result --> - jest obiektem, który posiada conajmniej jeden wiersz (num_rows) <-- zapytania typu SELECT
        //                                                  - posiada wartość == "true" (bool) <-- dla zapytań INSERT, UPDATE, DELETE
        //                                                    ORAZ stan BD został zmieniony (zaktualizowany, wstawiony, usunięty wiersz)

        // jeśli nie udało się wykonać zapytania, $result zwróci false;
        // ---------------------------------------------------------------------------------------------------------------------

        require "connect.php";

        mysqli_report(MYSQLI_REPORT_STRICT);

        try {

            $connection = new mysqli($host, $db_user, $db_password, $db_name);

            if ($connection->connect_errno) {

                throw new Exception(mysqli_connect_errno()); // failed to connect to DB;
                //throw new Exception($connection->error); // failed to connect to DB;

            } else {

                if (!is_array($values)) {
                    $values = [$values];
                }

                for($i = 0; $i < count($values); $i++) {
                    $values[$i] = mysqli_real_escape_string($connection, $values[$i]);
                }

                if ($result = $connection->query(vsprintf($query, $values))) {

                    if ($result instanceof mysqli_result) {

                        if ($result->num_rows) {

                            //$fun($result);
                            return $fun($result);

                        } //else {

                            // nie zrwócono żadnych wierszy ! (SELECT)
                        //}

                    } elseif ($result === true) { // (bool - true) - dla zapytań INSERT, UPDATE, DELETE ...

                        if ($connection->affected_rows) { // && $fun

                            if ($fun) {

                                return $fun($connection); // jeśli wymagane jest pobranie id ostatnio wstawionego wiersza - wywołaj funkcję;

                            } else {

                                return true;

                            }

                        } else {

                            return false;

                        }
                    }

                } else {

                    throw new Exception($connection->error);

                }

                $connection->close();

            }

        } catch(Exception $e) {

            return false;

        }
    }

?>
