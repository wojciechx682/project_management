<?php

/*
 *  common/pages/*.php — wspólne „szablony” stron
 *
 * Używają $GLOBALS['__PAGE_UI'] = 'admin' | 'manager' do wyboru: head, nav, view, template, skrypty (scripts/admin/... vs scripts/...).
 */

declare(strict_types=1);

$ui = $GLOBALS['__PAGE_UI'] ?? null;
if ($ui !== 'admin' && $ui !== 'manager') {
    exit('Invalid page context');
}

$root = dirname(__DIR__, 2);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["project-id"])) {
        $projectId = filter_input(INPUT_POST, "project-id", FILTER_SANITIZE_NUMBER_INT);

        if ($projectId) {
            $_SESSION["selected_project_id"] = $projectId;
            header("Location: project-details.php");
            exit();
        }
    }

    header("Location: projects.php");
    exit();
}

$projectId = $_SESSION["selected_project_id"] ?? null;

if (!$projectId) {
    header("Location: projects.php");
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
    require "$root/view/admin/project-details.php";
} else {
    require "$root/view/manager/project-details.php";
}
?>

</div>

<?php
if ($ui === 'admin') {
    require "$root/template/admin/edit-project-window.php";
} else {
    require "$root/template/edit-project-window.php";
}
?>

<?php require "$root/template/add-task-window.php"; ?>

<?php require "$root/template/add-comment-window.php"; ?>

<?php require "$root/template/edit-task-window.php"; ?>

<?php require "$root/template/delete-project.php"; ?>

<div id="task-details-window"></div>

<script src="../scripts/task-details.js"></script>

<script src="../scripts/edit-project.js"></script>

<?php if ($ui === 'admin') { ?>
<script src="../scripts/admin/add-task.js"></script>
<?php } else { ?>
<script src="../scripts/add-task.js"></script>
<?php } ?>

<script src="../scripts/toggleTaskOptions.js"></script>

<script src="../scripts/add-comment.js"></script>

<?php if ($ui === 'admin') { ?>
<script src="../scripts/admin/edit-task.js"></script>
<script src="../scripts/admin/delete-task.js"></script>
<?php } else { ?>
<script src="../scripts/edit-task.js"></script>
<script src="../scripts/delete-task.js"></script>
<?php } ?>

<script src="../scripts/delete-project.js"></script>

<script src="../scripts/search.js"></script>

</body>
</html>
