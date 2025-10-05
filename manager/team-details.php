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

            $teamId = filter_input(INPUT_POST, "team-id", FILTER_SANITIZE_NUMBER_INT);

            if ($teamId) {
                $_SESSION["selected_team_id"] = $teamId; // Zapisz ID zespołu do sesji
                header("Location: team-details.php"); // PRG - Przekierowanie GET
                exit();
            }
        }

        header("Location: teams.php"); // Jeśli brak ID zespołu, wróć do listy zespołów
        exit();
    }

    $teamId = $_SESSION["selected_team_id"] ?? null; // Pobierz ID zespołu z sesji (po przekierowaniu)

    if (!$teamId) {
        header("Location: teams.php");
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

<?php require "../template/edit-team-window.php"; ?>


<?php require "../template/add-user-window.php"; ?>

<?php /*require "../template/edit-task-window.php";*/ ?>

<!--<div id="task-details-window"></div>

<script src="../scripts/task-details.js"></script>-->

<script src="../scripts/edit-team.js"></script>

<script src="../scripts/add-user.js"></script>

<script src="../scripts/toggleUserOptions.js"></script>

<!--<script src="../scripts/edit-task.js"></script>-->

<script src="../scripts/delete-user.js"></script>

<script src="../scripts/search.js"></script>

</body>
</html>