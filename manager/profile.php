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

<?php require "../view/profile.php"; ?>

<script src="../scripts/update-profile.js?v=2"></script>

<script src="../scripts/manager/password-visibility.js"></script>

<script src="../scripts/search.js"></script>

</body>
</html>