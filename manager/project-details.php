<?php
    require_once "../start-session.php";
    // Sprawdzenie, czy zalogowany użytkownik to Project Manager
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
            header("Location: ../index.php");
                exit();
    }
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["project-id"])) {
            $projectId = filter_input(INPUT_POST, "project-id", FILTER_SANITIZE_NUMBER_INT);

            if ($projectId) {
                // Jeśli to zapytanie zwróci jakikolwiek wiersz (np. true), oznacza to, że zalogowany Project Manager jest powiązany z żądanym projektem.
                $result = query("
                        SELECT p.id
                        FROM user u
                        JOIN team_user tu ON u.id = tu.user_id
                        JOIN team t ON tu.team_id = t.id
                        JOIN project p ON t.id = p.team_id
                        WHERE u.id = ?  
                        AND p.id = ? 
                        AND u.role = 'Project Manager'
                    ",
                    "canUserViewProject",
                    [$_SESSION["id"], $_POST["project-id"]]);

                if ($result) {
                    $_SESSION["selected_project_id"] = $projectId; // Zapisz ID projektu do sesji
                        header("Location: project-details.php"); // PRG
                            exit();
                } else {
                    // Użytkownik (Project Manager) nie ma uprawnień do tego projektu lub projekt nie istnieje
                    $_SESSION["error_message"] = "Access Denied: You do not have permission to view this project, or the project does not exist";
                        header("Location: projects.php"); // Wróć do listy projektów
                            exit();
                }
            }
        }

        // Jeśli brak ID projektu, wróć do listy projektów
        $_SESSION["error_message"] = "Invalid request";
            header("Location: projects.php");
                exit();
    }

    $projectId = $_SESSION["selected_project_id"] ?? null; // Pobierz ID projektu z sesji (po przekierowaniu)

    if (!$projectId) {
        unset($_SESSION["selected_project_id"]); // Wyczyść na wszelki wypadek
            $_SESSION["info_message"] = "Access Denied or session expired. Please select a project";
                header("Location: projects.php");
                    exit();
    }

    // Jeśli wszystko OK, $projectId jest ID projektu, do którego użytkownik (Project Manager) ma dostęp
    // Możesz kontynuować z wyświetlaniem strony project-details.php
?>

<!DOCTYPE html>
<html lang="en">

<?php require "../view/manager/head-manager.php"; ?>

<body>

<div id="main-container">

<?php require "../view/nav.php"; ?>

<?php require "../view/header.php"; ?>

<?php require "../view/manager/project-details.php"; ?>

</div>

<?php require "../template/edit-project-window.php"; ?>

<?php require "../template/add-task-window.php"; ?>

<?php require "../template/edit-task-window.php"; ?>

<div id="task-details-window"></div>

<script src="../scripts/task-details.js"></script>

<script src="../scripts/edit-project.js"></script>

<script src="../scripts/add-task.js"></script>

<script src="../scripts/toggleTaskOptions.js"></script>

<script src="../scripts/edit-task.js"></script>

<script src="../scripts/delete-task.js"></script>

<script src="../scripts/delete-project.js"></script>

<?php require "../template/delete-project-window.php"; ?>

</body>
</html>