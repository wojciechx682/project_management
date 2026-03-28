<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php require "../view/admin/head-admin.php"; ?>

<body>

<div id="main-container">

    <?php require "../view/admin/nav-admin.php"; ?>

    <?php require "../view/header.php"; ?>

    <?php require "../view/admin/teams.php"; ?>

</div>

<?php require "../template/admin/add-team-window.php"; ?>

<script src="../scripts/admin/add-team.js"></script> <!-- pobranie listy PM'ów do znacznika select dynamicznie -->

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