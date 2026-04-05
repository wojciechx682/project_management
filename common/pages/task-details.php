<?php

declare(strict_types=1);

$ui = $GLOBALS['__PAGE_UI'] ?? null;
if ($ui !== 'admin' && $ui !== 'manager') {
    exit('Invalid page context');
}

$root = dirname(__DIR__, 2);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["task_id"])) {
        $taskId = filter_input(INPUT_POST, "task_id", FILTER_SANITIZE_NUMBER_INT);

        if ($taskId) {
            $_SESSION["selected_task_id"] = $taskId;
            header("Location: task-details.php");
            exit();
        }
    }

    header("Location: project-details.php");
    exit();
}

$taskId = $_SESSION["selected_task_id"] ?? null;

if (!$taskId) {
    header("Location: project-details.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
if ($ui === 'admin') {
    require "$root/view/admin/head-admin.php";
} else {
    require "$root/view/manager/head-manager.php";
}
?>

<body>

<div id="main-container">

<?php
if ($ui === 'admin') {
    require "$root/view/admin/nav-admin.php";
} else {
    require "$root/view/nav.php";
}
?>

<?php require "$root/view/header.php"; ?>

<?php
if ($ui === 'admin') {
    require "$root/view/admin/task-details.php";
} else {
    require "$root/view/manager/task-details.php";
}
?>

</div>

<?php require "$root/template/edit-comment-window.php"; ?>

<script src="../scripts/toggleCommentOptions.js"></script>

<script src="../scripts/edit-comment.js"></script>

<script src="../scripts/delete-comment.js"></script>

<script src="../scripts/search.js"></script>

</body>
</html>
