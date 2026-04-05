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
    require "$root/view/admin/projects.php";
} else {
    require "$root/view/manager/projects.php";
}
?>

</div>

<?php
if ($ui === 'admin') {
    require "$root/template/admin/add-project-window.php";
} else {
    require "$root/template/add-project-window.php";
}
?>

<script src="../scripts/add-project.js"></script>

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
