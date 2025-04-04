
<?php require_once "start-session.php"; ?>

<!DOCTYPE html>
<html lang="en">

<?php require "view/head.php"; ?>

<body>
    <div id="login">
        <form id="login-form" method="post" action="login.php">

            <span class="login-row">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </span>
            <span class="login-row">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="off">
            </span>
            <input type="submit" value="Log in">
            <span id="remind-password">
                <a href="#">Remind password</a>
            </span>
            <span id="register">
                <a href="register.php">Register</a>
            </span>
            <?php
                if (isset($_SESSION["invalid_credentials"])) {
                    echo $_SESSION["invalid_credentials"];
                    unset($_SESSION["invalid_credentials"]);
                }
            ?>

        </form>
    </div>
</body>
</html>