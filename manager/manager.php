<?php
    require_once "../start-session.php";
    require_role("Project Manager");
?>

<!DOCTYPE html>
<html lang="en">

<?php require "../view/manager/head-manager.php"; ?>

<body>

<?php require "../view/nav.php"; ?>

<?php require "../view/header.php"; ?>

<?php require "../view/manager/main.php"; ?>

<script src="../scripts/search.js"></script>

<script src="../scripts/toggle-sidebar.js"></script>

</body>
</html>