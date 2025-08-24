
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
            <!--<span class="login-row">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="off">
            </span>-->
            <span class="login-row">
                <label for="password">Password</label>

                    <!-- nowy wrapper: pozycjonuje przycisk wewnątrz inputa -->
                <span class="password-field">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                    >
                    <!-- przycisk pojawia się dopiero po wpisaniu znaków -->
                    <button
                        type="button"
                        class="toggle-password"
                        id="togglePass"
                        aria-label="Pokaż hasło"
                        aria-pressed="false"
                        hidden
                        title="Pokaż/ukryj hasło"
                    >
                    <i class="icon-eye" aria-hidden="true"></i>
                    </button>
                </span>
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

                if (isset($_SESSION["register-successful"])) {
                    echo '<span class="success">' . $_SESSION["register-successful"] . '</span>';
                    unset($_SESSION["register-successful"]);
                }
            ?>

        </form>
    </div>

    <script src="scripts/password-visibility.js"></script>

</body>
</html>