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
        if (isset($_POST["task_id"])) {
            $taskId = filter_input(INPUT_POST, "task_id", FILTER_SANITIZE_NUMBER_INT);

            if ($taskId) {
                $_SESSION["selected_task_id"] = $taskId; // Zapisz ID projektu do sesji
                header("Location: task-details.php"); // PRG - Przekierowanie GET
                exit();
            }
        }

        header("Location: project-details.php"); // Jeśli brak ID projektu, wróć do listy projektów
        exit();
    }

    $taskId = $_SESSION["selected_task_id"] ?? null; // Pobierz ID projektu z sesji (po przekierowaniu)

    if (!$taskId) {
        header("Location: project-details.php");
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

<?php require "../view/manager/task-details.php"; ?>

</div>

<?php require "../template/edit-comment-window.php"; ?>

<script src="../scripts/toggleCommentOptions.js"></script>

<script src="../scripts/edit-comment.js"></script>

<script src="../scripts/delete-comment.js"></script>

<script src="../scripts/search.js"></script>

</body>
</html>