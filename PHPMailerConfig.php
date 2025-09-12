<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $mail = new PHPMailer(true);
    $mail->isSMTP();                                   // Używaj SMTP
    $mail->Host = 'smtp.gmail.com';                    // Serwer SMTP Gmail:contentReference[oaicite:1]{index=1}
    $mail->SMTPAuth = true;                            // Włącz uwierzytelnianie SMTP:contentReference[oaicite:2]{index=2}
    $mail->Username   = 'twoj_adres@gmail.com';        // Nazwa użytkownika (adres Gmail):contentReference[oaicite:3]{index=3}
    $mail->Password   = 'hasło_aplikacji';             // Hasło aplikacyjne do Gmail:contentReference[oaicite:4]{index=4}
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;// Szyfrowanie TLS:contentReference[oaicite:5]{index=5}
    $mail->Port       = 587;                           // Port SMTP Gmail (TLS)
    $mail->setFrom('twoj_adres@gmail.com', 'Nazwa Aplikacji');
    $mail->addAddress($userEmail);                     // adres email użytkownika
    // $mail->Subject = 'Password reset';
    // $mail->Body    = 'Treść wiadomości...';
