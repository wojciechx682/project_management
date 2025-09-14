
<?php require_once "start-session.php"; ?>

<?php
    // 1. Pobranie tokenu z parametru URL i wstępna walidacja
    if (!isset($_GET["token"]) || empty($_GET["token"])) {
        // Brak tokenu w URL lub jest pusty – przekierowanie z komunikatem o błędzie
        $_SESSION["reset-error"] = '<span class="error">Invalid or missing password reset token</span>';
        header("Location: forgot-password.php");
        exit();
    }
    $token = trim($_GET["token"]);
    // Opcjonalna dodatkowa walidacja formatu tokenu (32 znaki hexadecymalne)
    if (!ctype_xdigit($token) || strlen($token) !== 32) {
        $_SESSION["reset-error"] = '<span class="error">Invalid password reset token</span>';
        header("Location: forgot-password.php");
        exit();
    }

    // 2. Weryfikacja tokenu w bazie danych
    $tokenHashed = hash("sha256", $token);
    $resetEntry = query("SELECT * FROM password_resets WHERE token=? AND expires_at >= NOW()","fetchPasswordResetEntry", $tokenHashed);

    if (!$resetEntry) {
        // Nie znaleziono pasującego rekordu (token nieprawidłowy lub wygasły)
        $_SESSION["reset-error"] = '<span class="error">This password reset link is invalid or has expired</span>';
        header("Location: forgot-password.php");
        exit();
    }
    // Jeśli token poprawny, z bazy otrzymamy m.in. email użytkownika i datę wygaśnięcia
    $userEmail = $resetEntry["email"];

    // 3. Obsługa wysłania formularza nowego hasła
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["new_password"], $_POST["confirm_password"])) {
            $newPassword = $_POST["new_password"];
            $confirmPassword = $_POST["confirm_password"];

            // Walidacja nowego hasła (np. minimalna długość i zgodność obu pól)
            /*if (strlen($newPassword) < 8) {
                $_SESSION["reset-error"] = '<span class="error">Password must be at least 8 characters long.</span>';
                // Przeładuj formularz z powrotem (zachowujemy token w URL, żeby nie wygasł)
                header("Location: reset-password.php?token=" . urlencode($token));
                exit();
            }*/
            $passRegex = '/^((?=.*[!@#$%^&_*+-\/\?])(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])).{10,31}$/';
            if (!preg_match($passRegex, $newPassword)) {
                $_SESSION["reset-error"] = '<span class="error">The password must be between 10 and 30 characters long, contain at least one uppercase letter, one lowercase letter, one number and one special character</span>';
                header("Location: reset-password.php?token=" . urlencode($token));
                exit();
            }

            if ($newPassword !== $confirmPassword) {
                $_SESSION["reset-error"] = '<span class="error">New password and confirmation do not match</span>';
                header("Location: reset-password.php?token=" . urlencode($token));
                exit();
            }

            // 4. Zapisanie nowego hasła do bazy (hashujemy hasło przed zapisem)
            $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateSuccess = query("UPDATE user SET password=? WHERE email=?","", [$newPasswordHashed, $userEmail]);
            if (!$updateSuccess) {
                // Nie udało się zaktualizować hasła w bazie
                $_SESSION["reset-error"] = '<span class="error">An error occurred. Password not updated</span>';
                header("Location: reset-password.php?token=" . urlencode($token));
                exit();
            }

            // 5. Usunięcie tokenów resetujących dla tego użytkownika (unieważnienie linków)
            query("DELETE FROM password_resets WHERE email=?", "", $userEmail);

            // 6. Ustawienie komunikatu o sukcesie i przekierowanie na stronę logowania
            $_SESSION["reset-success"] = "Your password has been reset successfully. You can now log in with the new password";
            header("Location: index.php");
            exit();
        }
    }
    // Jeśli to żądanie GET (pierwsze wejście na stronę) lub wystąpił błąd walidacji, wyświetlamy formularz
?>
<!DOCTYPE html>
<html lang="en">
<?php require "view/head.php"; ?>
<body>
<div id="reset-password-form">
    <!--<h2>Set a New Password</h2>-->
    <form id="reset-form" method="post" action="">
            <span class="reset-row">
                <label for="new_password">New password</label>
                <input type="password" name="new_password" id="new_password" required>
            </span>
        <span class="reset-row">
                <label for="confirm_password">Confirm password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </span>
        <!-- Przekazujemy token jako ukryte pole, aby było dostępne przy POST (alternatywnie można trzymać w URL) -->
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <input type="submit" value="Update password">
        <span id="back-to-login">
                <a href="index.php">Back to login</a>
            </span>
        <?php
            // Wyświetlanie ewentualnego komunikatu błędu (np. token wygasł lub walidacja hasła nie przeszła)
            if (isset($_SESSION["reset-error"])) {
                echo $_SESSION["reset-error"];
                unset($_SESSION["reset-error"]);
            }
        ?>
    </form>
</div>
</body>
</html>
