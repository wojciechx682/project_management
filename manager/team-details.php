<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["team-id"])) {
            $projectId = filter_input(INPUT_POST, "team-id", FILTER_SANITIZE_NUMBER_INT);

            if ($projectId) {
                $_SESSION["selected_team_id"] = $projectId; // Zapisz ID projektu do sesji
                    header("Location: team-details.php"); // PRG - Przekierowanie GET
                        exit();
            }
        }

        header("Location: projects.php"); // Jeśli brak ID projektu, wróć do listy projektów
        exit();
    }

    $projectId = $_SESSION["selected_team_id"] ?? null; // Pobierz ID projektu z sesji (po przekierowaniu)

    if (!$projectId) {
        header("Location: projects.php");
            exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php require "../view/manager/head-manager.php"; ?>

<body>

<div id="main-container">

<?php require "../view/nav.php"; ?>

<?php require "../view/header.php"; ?>

<?php require "../view/manager/team-details.php"; ?>

</div>

<?php /*require "../template/edit-project-window.php";*/ ?>


<?php /*require "../template/add-task-window.php";*/ ?>

<?php /*require "../template/edit-task-window.php";*/ ?>

<!--<div id="task-details-window"></div>

<script src="../scripts/task-details.js"></script>

<script src="../scripts/edit-project.js"></script>

<script src="../scripts/add-task.js"></script>

<script src="../scripts/toggleTaskOptions.js"></script>

<script src="../scripts/edit-task.js"></script>

<script src="../scripts/delete-task.js"></script>-->

</body>
</html>