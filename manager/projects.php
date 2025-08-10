<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }
?>

<?php
// Na początku pliku (po otwarciu sesji)
if (isset($_GET['delete_success'])) {
    echo '<div class="success-message">Project deleted successfully!</div>';
}
?>

<!DOCTYPE html>
<html lang="en">

<?php require "../view/manager/head-manager.php"; ?>

<body>

<div id="main-container">

<?php require "../view/nav.php"; ?>

<?php require "../view/header.php"; ?>

<?php require "../view/manager/projects.php"; ?>

</div>

<?php require "../template/add-project-window.php"; ?>

<script src="../scripts/add-project.js"></script>

</body>
</html>