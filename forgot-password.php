
<?php require_once "start-session.php"; ?>

<!DOCTYPE html>
<html lang="en">

<?php require "view/head.php"; ?>  <!-- Nagłówek z odnośnikami do CSS, itp. -->

<body>
    <div id="reset">  <!-- kontener podobny do #login/#register -->
        <form id="reset-form" method="post" action="forgot-password.php">
                <span class="reset-row">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" required>
                </span>
            <!-- (Opcjonalnie: reCaptcha podobnie jak na logowaniu) -->
            <input type="submit" value="Reset password">
            <span id="log-in">
                    <a href="index.php">Back to login</a>
                </span>
            <?php
            // Tutaj można wyświetlać komunikaty o błędach/sukcesach poprzez $_SESSION (patrz krok 6)
            ?>
        </form>
    </div>
</body>
</html>
