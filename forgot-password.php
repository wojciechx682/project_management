
<?php require_once "start-session.php"; ?>

<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["email"])) {
            //Validate Email address
            $email = trim($_POST["email"]);
            //$email = str_replace(str_split(" "), "", $email);
            $emailSanitized = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (!filter_var($emailSanitized, FILTER_VALIDATE_EMAIL) || ($emailSanitized !== $email)) {
                $_SESSION["reset-error"] = '<span class="error">Please provide a valid email address</span>';
                header("Location: forgot-password.php");
                exit();
            }
            // Check if user exists in DB
            $userExists = query("SELECT user.id FROM user WHERE email=?", "verifyEmailExists", $emailSanitized); // return TRUE if email exists, otherwise return NULL
            if (!$userExists) {
                $_SESSION["reset-error"] = '<span class="error">Account with this email not found</span>';
                header("Location: forgot-password.php");
                exit();
            }
            // Generate Token
            $token = bin2hex(random_bytes(16)); // 32-znakowy losowy token
            $expires = date("Y-m-d H:i:s", time() + 900); // aktualny czas + 900 sekund


        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php require "view/head.php"; ?>  <!-- Nagłówek z odnośnikami do CSS, itp. -->

<body>
    <div id="reset">
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
                if (isset($_SESSION["reset-error"])) {
                    echo $_SESSION["reset-error"];
                    unset($_SESSION["reset-error"]);
                }
            ?>
        </form>
    </div>
</body>
</html>
