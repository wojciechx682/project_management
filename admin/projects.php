<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php require "../view/admin/head-admin.php"; ?>

<body>

<div id="main-container">

<?php require "../view/admin/nav-admin.php"; ?>

<?php require "../view/header.php"; ?>

<?php require "../view/admin/projects.php"; ?>

</div>

<?php require "../template/add-project-window.php"; ?>

<script src="../scripts/add-project.js"></script>

<script src="../scripts/search.js"></script>

</body>
</html>