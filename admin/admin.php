<?php
    require_once "../start-session.php";
    require_role("Admin");
?>

<!DOCTYPE html>
<html lang="en">

<?php require "../view/admin/head-admin.php"; ?>

<body>

<?php require "../view/admin/nav-admin.php"; ?>

<?php require "../view/header.php"; ?>

<?php require "../view/admin/main.php"; ?>

</body>
</html>