<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Team Member") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
            header("Location: ../index.php");
                exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php require "../view/user/head-user.php"; ?>

<body>

<?php require "../view/user/nav-user.php"; ?>

<?php require "../view/header.php"; ?>

<?php require "../view/main.php"; ?>

</body>
</html>