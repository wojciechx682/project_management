<?php

    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Team Member") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["task_id"])) {
            $taskId = filter_input(INPUT_POST, "task_id", FILTER_SANITIZE_NUMBER_INT);

            if ($taskId) {
                $_SESSION["selected_task_id"] = $taskId; // Zapisz ID projektu do sesji
                header("Location: task-details.php"); // PRG - Przekierowanie GET
                exit();
            }
        }

        header("Location: tasks.php"); // Jeśli brak ID projektu, wróć do listy projektów
        exit();
    }

    $taskId = $_SESSION["selected_task_id"] ?? null; // Pobierz ID projektu z sesji (po przekierowaniu)

    if (!$taskId) {
        header("Location: tasks.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php require "../view/user/head-user.php"; ?>

<body>

<div id="main-container">

<?php require "../view/user/nav-user.php"; ?>

<?php require "../view/header.php"; ?>

<?php require "../view/user/task-details.php"; ?>

</div>

<script src="../scripts/search.js"></script>

</body>
</html>