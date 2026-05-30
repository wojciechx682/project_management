<?php
    require_once "../start-session.php";
    require_role("Team Member");
?>

<!DOCTYPE html>
<html lang="en">

<?php require "../view/user/head-user.php"; ?>

<body>

<?php require "../view/user/nav-user.php"; ?>

<?php require "../view/header.php"; ?>

<?php require "../view/user/main.php"; ?>

</body>
</html>