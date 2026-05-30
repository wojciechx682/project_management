<?php
    require_once "../start-session.php";
    require_role("Team Member");
?>

<!DOCTYPE html>
<html lang="en">

<?php require "../view/user/head-user.php"; ?>

<body>

<div id="main-container">

<?php require "../view/user/nav-user.php"; ?>

<?php require "../view/header.php"; ?>

<?php require "../view/user/tasks-kanban.php"; ?>

</div>

<?php require "../template/add-comment-window.php"; ?>

<?php require "../template/user/change-status-window.php"; ?>

<!--<script src="../scripts/showNoTasksMessage.js"></script>-->

<script src="../scripts/toggleTaskOptions.js"></script>

<script src="../scripts/add-comment.js"></script>

<script src="../scripts/update-status.js"></script>

</body>
</html>