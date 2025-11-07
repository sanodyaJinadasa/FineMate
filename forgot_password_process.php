<?php
session_start();
require 'db_connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // if installed via Composer
// OR if manually installed:
// require 'PHPMailer/src/PHPMailer.php';
// require 'PHPMailer/src/SMTP.php';
// require 'PHPMailer/src/Exception.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['resetEmail']);

    // Check if email exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($user) {
        $token = bin2hex(random_bytes(32));
            date_default_timezone_set('Asia/Colombo');

        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Save token
        $update = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
        $update->execute([$token, $expires, $email]);

        $reset_link = "http://localhost/finemate/reset_password.php?token=" . $token;

        // Send email with PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'sanodyav@gmail.com'; // your Gmail
            $mail->Password   = 'pqau amrg zsim mmas'; // your App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('sanodyav@gmail.com', 'FineMate Support');
            $mail->addAddress($email, $user['name']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset - FineMate';
            $mail->Body    = "
                <p>Hi <strong>{$user['name']}</strong>,</p>
                <p>We received a request to reset your password. Click below to reset it:</p>
                <p><a href='$reset_link' target='_blank'>$reset_link</a></p>
                <p>This link will expire in 1 hour.</p>
                <p>If you didn't request this, please ignore this email.</p>
                <br>
                <p>— FineMate Support</p>
            ";

            $mail->send();
            $_SESSION['msg'] = "✅ Password reset link sent to your email.";
        } catch (Exception $e) {
            $_SESSION['msg'] = "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['msg'] = "❌ Email address not found.";
    }

    header("Location: login.php");
    exit();
}
?>
