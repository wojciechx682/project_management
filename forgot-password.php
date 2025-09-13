
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
            $tokenHashed = hash("sha256", $token); // hash user token using sha256 algorithm;
            $expires = date("Y-m-d H:i:s", time() + 900); // aktualny czas + 900 sekund

            $resetData = [$email, $tokenHashed, $expires];

            $insertSuccessful = query("INSERT INTO password_resets (id, email, token, expires_at) VALUES (NULL, ?, ?, ?)","insertToken", $resetData);

            if (!$insertSuccessful) {
                $_SESSION["reset-error"] = '<span class="error">An error occurred while generating the verification code. Please try again.</span>';
                header("Location: forgot-password.php");
                exit();
            }

            // Sending email with reset link
            $userEmail = $emailSanitized;
            //$resetLink = "http://your-domain.com/reset-password.php?token=$token";  // URL do strony resetu z tokenem
            $resetLink = "localhost/project_management/reset-password.php?token=$token";  // URL do strony resetu z tokenem

            try {
                require "PHPMailerConfig.php";           // konfiguracja PHPMailer (SMTP, nadawca itp.)

                $message = "
                            <html>
                                <head>
                                    <title>Reset password</title>
                                </head>
                                <body>
                                    <p>Hello <b>$userEmail</b>, </p>                            
                                    <p>You requested a password reset for your account.</p>                               
                                    <p>Click the link below to set a new password (valid for 15 minutes):</p>                           
                                    <p><a href='$resetLink'>$resetLink</a></p>
                                    <p>If you did not request this, you can ignore this email.</p>                                    
                                </body>                  
                            </html>
                        ";

                $subject = "Password reset request";

                if (sendEmail($message, $userEmail, $subject)) { // email wysłany pomyślnie
                    $_SESSION["reset-success"] = '<span class="reset-success success">An email with password reset instructions has been sent to your address.</span>';
                    header("Location: forgot-password.php");
                    exit();
                } else { // nie udało się wysłać wiadomości e-mail;
                    $_SESSION["reset-error"] = '<span class="error">Failed to send reset email. Please try again.</span>';; // email niewysłany, wystąpił błąd;
                    header("Location: forgot-password.php");
                    exit();
                }

            } catch (Exception $e) {
                // Jeśli wysyłka e-mail nie powiodła się, usuwamy wcześniej dodany token i informujemy użytkownika
                query("DELETE FROM password_resets WHERE email=? AND token=?", "", [$userEmail, $tokenHashed]);
                $_SESSION["reset-error"] = '<span class="error">Failed to send reset email. Please try again.</span>';
                header("Location: forgot-password.php");
                exit();
            }



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

                if (isset($_SESSION["reset-success"])) {
                    echo $_SESSION["reset-success"];
                    unset($_SESSION["reset-success"]);
                }
            ?>
        </form>
    </div>
</body>
</html>
