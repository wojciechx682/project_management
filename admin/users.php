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

<?php require "../view/admin/users.php"; ?>

</div>

<script src="../scripts/admin/toggleUserOptions.js"></script>

<?php require "../template/manage-user-options.php"; ?>

<script src="../scripts/admin/showModalBox.js"></script>

</body>
</html>