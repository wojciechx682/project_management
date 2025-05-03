<?php

    function log_in($result) {

        unset($_SESSION["invalid_credentials"]);

        //$row = $result->fetch_assoc();

        $row = $result->fetch(PDO::FETCH_ASSOC);

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
        //while ($row = $result->fetch_assoc()) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            //$startDate = DateTime::createFromFormat('Y-m-d', $row["start_date"])->format('d-m-Y');
            //$endDate = DateTime::createFromFormat('Y-m-d', $row["end_date"])->format('d-m-Y');
            $startDate = DateTime::createFromFormat('Y-m-d', $row["start_date"])->format('j F Y');
            $endDate = DateTime::createFromFormat('Y-m-d', $row["end_date"])->format('j F Y');
            echo sprintf($project, $row["id"], $row["id"], $row["name"], $row["description"], $startDate, $endDate, $row["status"], $row["team_name"]);
        }
    }

    function getProjectDetails($result) {
        //require "../view/manager/project-header.php"; // table header;
        $projectDetails = file_get_contents("../template/project-details.php");
        //$row = $result->fetch_assoc();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        //$startDate = DateTime::createFromFormat('Y-m-d', $row["start_date"])->format('d-m-Y');
        //$endDate = DateTime::createFromFormat('Y-m-d', $row["end_date"])->format('d-m-Y');
        $startDate = DateTime::createFromFormat('Y-m-d', $row["start_date"])->format('j F Y');
        $endDate = DateTime::createFromFormat('Y-m-d', $row["end_date"])->format('j F Y');
        $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $row["created_at"])->format('j F Y, H:i');
        $updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $row["updated_at"])->format('j F Y, H:i');
        echo sprintf($projectDetails, $row["id"], $row["name"], $row["description"], $startDate, $endDate, $row["status"], $createdAt, $updatedAt, $row["team_name"]);
    }

    function getTaskDetails($result) {
        require "../view/manager/task-header.php"; // table header;
        $taskDetails = file_get_contents("../template/task-details.php");
        //while ($row = $result->fetch_assoc()) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            // Oczyszczanie danych
            $id = htmlspecialchars($row["id"], ENT_QUOTES, "UTF-8");
            $title = htmlspecialchars($row["title"], ENT_QUOTES, "UTF-8");
            $priority = htmlspecialchars($row["priority"], ENT_QUOTES, "UTF-8");
            $status = htmlspecialchars($row["status"], ENT_QUOTES, "UTF-8");
            $firstName = htmlspecialchars($row["first_name"], ENT_QUOTES, "UTF-8");
            $lastName = htmlspecialchars($row["last_name"], ENT_QUOTES, "UTF-8");
            // Formatowanie dat
            $dueDate = DateTime::createFromFormat('Y-m-d', $row["due_date"])->format('j F Y');
            $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $row["created_at"])->format('j F Y, H:i');
            $updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $row["updated_at"])->format('j F Y, H:i');
            echo sprintf($taskDetails, $id, $id, $title, $priority, $status, $dueDate, $firstName, $lastName, $createdAt, $updatedAt);
        }
    }

    function getTasks($result) {
        $taskDetails = file_get_contents("../template/task-details-window.php");
        //$row = $result->fetch_assoc();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        // Oczyszczanie wszystkich danych przed wyświetleniem
        $id = htmlspecialchars($row["id"], ENT_QUOTES, "UTF-8");
        $title = htmlspecialchars($row["title"], ENT_QUOTES, "UTF-8");
        $description = htmlspecialchars($row["description"], ENT_QUOTES, "UTF-8");
        $priority = htmlspecialchars($row["priority"], ENT_QUOTES, "UTF-8");
        $status = htmlspecialchars($row["status"], ENT_QUOTES, "UTF-8");
        $projectName = htmlspecialchars($row["name"], ENT_QUOTES, "UTF-8");
        $firstName = htmlspecialchars($row["first_name"], ENT_QUOTES, "UTF-8");
        $lastName = htmlspecialchars($row["last_name"], ENT_QUOTES, "UTF-8");
        // Formatowanie dat (już po oczyszczaniu)
        $dueDate = DateTime::createFromFormat('Y-m-d', $row["due_date"])->format('j F Y');
        $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $row["created_at"])->format('j F Y, H:i');
        $updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $row["updated_at"])->format('j F Y, H:i');
        echo sprintf($taskDetails, $id, $title, $description, $priority, $status, $dueDate, $projectName, $firstName, $lastName, $createdAt, $updatedAt);
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
        $users = file_get_contents("../template/pendingUsers.php");

        //while ($row = $result->fetch_assoc()) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $isApproved = "Pending"; // <- Zawsze "Pending", bo zapytanie wybiera tylko is_approved = 0
            echo sprintf($users, $row["id"], $row["first_name"], $row["last_name"], $row["email"], $row["role"], $row["created_at"], $isApproved, $row["id"], $row["id"]);
        }
    }

    function createTeamSelectList($result) {
        //while($row = $result->fetch_assoc()) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="'.$row["id"].'">'.$row["name"].'</option>';
        }
    }

    function createUserSelectList($result) {
        //while($row = $result->fetch_assoc()) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="'.$row["id"].'">'.$row["first_name"].' '.$row["last_name"].'</option>';
        }
    }

    function addNewProject($connection) {
        //return $connection->insert_id;
        return $connection->lastInsertId();
    }

    function addNewTask($connection) {
        //return $connection->insert_id;
        return $connection->lastInsertId();
    }

    function getTaskInfo($result) {
        //return $result->fetch_assoc();
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    function getTeamName($result) {
        //$row = $result->fetch_assoc();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row["name"];
    }

    function getProjectForEdit($result) {
        return $result;
    }

    function updateProject($connection) {
        return true; // lub bardziej złożona logika jeśli potrzebna
    }

    function verifyTaskExists($result) {
        return true;
    }

    function query($query, $fun, $values) {

        require "connect.php";

        try {
            $connection = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (!is_array($values)) {
                $values = [$values];
            }

            $stmt = $connection->prepare($query);
            $stmt->execute($values);

            if ($stmt->columnCount() > 0) { // SELECT
                if ($stmt->rowCount() > 0) {
                    return $fun($stmt);
                } else {
                    return null;
                }
            } else { // INSERT, UPDATE, DELETE
                if ($stmt->rowCount() > 0) {
                    return $fun ? $fun($connection) : true;
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /*function query($query, $fun, $values) {

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

                        } else {
                            // nie zrwócono żadnych wierszy ! (SELECT)
                            return null;
                        }

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
    }*/

?>
