<?php

    function logIn($result) {

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
            $_SESSION["is_approved"] = $row["is_approved"];

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

    function getAllTeamsForProjectManager($result) {
        require "../view/manager/teams-header.php"; // table header;
        $team = file_get_contents("../template/team.php");
        //while ($row = $result->fetch_assoc()) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $row["created_at"])->format('j F Y, H:i');
            echo sprintf($team, $row["id"], $row["id"], $row["name"], $createdAt, $row["members_count"]);
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
            //$updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $row["updated_at"])->format('j F Y, H:i');
            echo sprintf($taskDetails, $id, $id, $title, $priority, $status, $dueDate, $firstName, $lastName, $createdAt, $id, $id);
        }
    }

    function getTeamMembers($result) {
        require "../view/manager/team-header.php"; // table header;
        $teamDetails = file_get_contents("../template/team-members-details.php");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            // Oczyszczanie danych
            $id = htmlspecialchars($row["user_id"], ENT_QUOTES, "UTF-8");
            $firstName = htmlspecialchars($row["first_name"], ENT_QUOTES, "UTF-8");
            $lastName = htmlspecialchars($row["last_name"], ENT_QUOTES, "UTF-8");
            $email = htmlspecialchars($row["email"], ENT_QUOTES, "UTF-8");
            $role = htmlspecialchars($row["role"], ENT_QUOTES, "UTF-8");
            //$createdAt = htmlspecialchars($row["created_at"], ENT_QUOTES, "UTF-8");
            $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $row["created_at"])->format('j F Y, H:i');
            //$updatedAt = htmlspecialchars($row["updated_at"], ENT_QUOTES, "UTF-8");
            $updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $row["updated_at"])->format('j F Y, H:i');
            echo sprintf($teamDetails, $id, $firstName, $lastName, $email, $role, $createdAt, $updatedAt);
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

    function getTeamDetails($result) {
        //require "../view/manager/project-header.php"; // table header;
        $teamDetails = file_get_contents("../template/team-details.php");
        //$row = $result->fetch_assoc();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        //$startDate = DateTime::createFromFormat('Y-m-d', $row["start_date"])->format('d-m-Y');
        //$endDate = DateTime::createFromFormat('Y-m-d', $row["end_date"])->format('d-m-Y');
        $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $row["created_at"])->format('j F Y, H:i');
        echo sprintf($teamDetails, $row["id"], $row["name"], $createdAt, $row["members_count"], $row["leader_name"]);
    }

    function verifyEmailExists($result) {
        return true;
    }

    function register($connection) {
        $_SESSION["register-successful"] = "The new account has been successfully created and is waiting for approval from the System Administrator";
        return true;
    }

    function insertToken($connection) {
        return true;
    }

    function getPendingUsers($result) {
        require "../view/admin/users-header.php"; // table header;
        $users = file_get_contents("../template/pendingUsers.php");

        //while ($row = $result->fetch_assoc()) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $row["created_at"])->format('j F Y, H:i');
            $isApproved = "Pending"; // <- Zawsze "Pending", bo zapytanie wybiera tylko is_approved = 0
            echo sprintf($users, $row["id"], $row["first_name"], $row["last_name"], $row["email"], $row["role"], $createdAt, $isApproved, $row["id"], $row["id"]);
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

    function addNewTeam($connection) {
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

    function getTaskForEdit($result) {
        return $result;
    }

    function getTeamForEdit($result) {
        return $result;
    }

    function updateProject($connection) {
        return true; // lub bardziej złożona logika jeśli potrzebna
    }

    function updateTask($connection) {
        return true; // lub bardziej złożona logika jeśli potrzebna
    }

    function verifyTaskExists($result) {
        return true;
    }

    function deleteTask($connection) {
        return true;
    }

    function verifyRecaptcha($captchaToken) {

        if (!$captchaToken) return false;

        $response = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.RECAPTCHA_SECRET_KEY.'&response='.$_POST['g-recaptcha-response']));

        if (!$response->success) {
            return false;
        }

        return true;
    }

    function displayNumberOfProjects($result) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $numberOfProjects = "";
        if ($row["numberOfProjects"] >= 2) {
            $numberOfProjects = "Projects";
        } else {
            $numberOfProjects = "Project";
        }
        echo '<span class="projects-info">'.$row["numberOfProjects"]." ".$numberOfProjects.'</span>';
    }

    function displayNumberOfTasks($result) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $numberOfTasks = "";
        if ($row["numberOfTasks"] >= 2) {
            $numberOfTasks = "Tasks";
        } else {
            $numberOfTasks = "Task";
        }
        echo '<span class="projects-info">'.$row["numberOfTasks"]." ".$numberOfTasks.'</span>';
    }

    function fetchPasswordResetEntry($result) {
        // Zwraca pierwszy (powinien być tylko jeden) znaleziony wpis resetu hasła jako tablicę asocjacyjną
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    function updateProfile($connection) {
        $userId = $_SESSION["id"]; // ID masz w sesji
        // W tym miejscu $connection to nadal PDO, którego użyto do UPDATE
        $stmt = $connection->prepare("SELECT updated_at FROM user WHERE id=?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn(); // zwraca timestamp w formacie 'YYYY-MM-DD HH:MM:SS'
    }

    function fetchPasswordHash($result) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row["password"];
    }

    function getTeamMembersCount($result) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row["members_count"];
    }

    function checkIfTeamNameExists($result) {
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row["name"];
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
            // PROD
            //error_log('Database error: '.$e->getMessage());
            //echo "<span style='color: red;'>A server error occurred. Please try again later</span>";
            //return false;
            // DEV
            error_log('Database error: ' . $e->getMessage());
            echo "<span style='color: red;'>An exception occurred in the database: {$e->getMessage()}<br>File: {$e->getFile()}<br>Line: {$e->getLine()}</span>";
            return false;
        }
    }