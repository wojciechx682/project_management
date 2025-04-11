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

<?php require "../view/admin/nav-admin.php"; ?>

<?php require "../view/header.php"; ?>

<?php require "../view/main.php"; ?>

</body>
</html>