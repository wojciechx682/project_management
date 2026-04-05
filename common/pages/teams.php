<?php

declare(strict_types=1);

$ui = $GLOBALS['__PAGE_UI'] ?? null;
if ($ui !== 'admin' && $ui !== 'manager') {
    exit('Invalid page context');
}

$root = dirname(__DIR__, 2);
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
        require "$root/view/admin/teams.php";
    } else {
        require "$root/view/manager/teams.php";
    }
    ?>

</div>

<?php
if ($ui === 'admin') {
    require "$root/template/admin/add-team-window.php";
} else {
    require "$root/template/add-team-window.php";
}
?>

<?php if ($ui === 'admin') { ?>
<script src="../scripts/admin/add-team.js"></script>
<?php } else { ?>
<script src="../scripts/add-team.js"></script>
<?php } ?>

<script src="../scripts/search.js"></script>

<script>

    const result = document.getElementById("result");

    if (result && result.innerHTML.trim() !== "") {
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    }

</script>

</body>
</html>
