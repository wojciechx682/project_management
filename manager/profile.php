<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php require "../view/manager/head-manager.php"; ?>

<body>

<?php require "../view/nav.php"; ?>

<?php require "../view/header.php"; ?>

<?php require "../view/profile.php"; ?>

<script src="../scripts/update-profile.js"></script>

</body>
</html>