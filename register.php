
<?php require_once "start-session.php"; ?>

<!DOCTYPE html>
<html lang="en">

<?php require "view/head.php"; ?>

<body>
    <div id="login">
        <form id="register-form" method="post" action="register-user.php">
            <span class="register-row">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" required>
            </span>
            <span class="register-row">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" required>
            </span>
            <span class="register-row">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>
            </span>
            <span class="register-row">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="off">
            </span>
            <span class="register-row">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required autocomplete="off">
            </span>
            <span class="register-row">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="team_member">Team Member</option>
                    <option value="project_manager">Project Manager</option>
                    <option value="administrator">Administrator</option>
                </select>
            </span>
            <input type="submit" value="Register">
            <!--<span id="remind-password">
                <a href="#">Remind password</a>
            </span>-->
            <span id="log-in">
                <a href="index.php">Log in</a>
            </span>
            <?php
                if (isset($_SESSION["register-error"])) {
                    echo '<span class="error register-error">' . $_SESSION["register-error"] . '</span>';
                    unset($_SESSION["register-error"]);
                }
            ?>
        </form>
    </div>
</body>
</html>