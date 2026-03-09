<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php require "../view/manager/head-manager.php"; ?>

<body>

<div id="main-container">

<?php require "../view/nav.php"; ?>

<?php require "../view/header.php"; ?>

<?php require "../view/manager/projects.php"; ?>

</div>

<?php require "../template/add-project-window.php"; ?>

<script src="../scripts/add-project.js"></script>

<script src="../scripts/search.js"></script>

<!--<script>
    setTimeout(() => {
        const result = document.getElementById("result").innerHTML;
        if (result) {
            result.innerHTML = "";
            //result.style.display = "none";
            window.location.reload();
        }
    }, 1500);
</script>-->

<script>
    /*let result = document.getElementById("result").innerHTML;

    if (result) {

        result.innerHTML = null;

        setTimeout(() => {
            window.location.reload();
        }, 1500);
    }*/

    const result = document.getElementById("result");

    if (result && result.innerHTML.trim() !== "") {

        setTimeout(() => {
            window.location.reload();
        }, 1500);

    }

</script>

</body>
</html>