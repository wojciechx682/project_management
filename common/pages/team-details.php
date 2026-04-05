<?php

declare(strict_types=1);

$ui = $GLOBALS['__PAGE_UI'] ?? null;
if ($ui !== 'admin' && $ui !== 'manager') {
    exit('Invalid page context');
}

$root = dirname(__DIR__, 2);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["team-id"])) {

        $teamId = filter_input(INPUT_POST, "team-id", FILTER_SANITIZE_NUMBER_INT);

        if ($teamId) {
            $_SESSION["selected_team_id"] = $teamId;
            header("Location: team-details.php");
            exit();
        }
    }

    header("Location: teams.php");
    exit();
}

$teamId = $_SESSION["selected_team_id"] ?? null;

if (!$teamId) {
    header("Location: teams.php");
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
    require "$root/view/admin/team-details.php";
} else {
    require "$root/view/manager/team-details.php";
}
?>

</div>

<?php require "$root/template/edit-team-window.php"; ?>

<?php require "$root/template/add-user-window.php"; ?>

<?php require "$root/template/delete-team.php"; ?>

<script src="../scripts/edit-team.js"></script>

<script src="../scripts/add-user.js"></script>

<script src="../scripts/toggleUserOptions.js"></script>

<script src="../scripts/delete-user.js"></script>

<script src="../scripts/search.js"></script>

<script src="../scripts/delete-team.js"></script>

</body>
</html>
