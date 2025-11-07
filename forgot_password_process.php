<?php
session_start();
require 'db_connect.php'; // your PDO connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['resetEmail']);

    // Step 1: Check if the email exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Step 2: Generate a token
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Step 3: Save token in DB
        $update = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
        $update->execute([$token, $expires, $email]);

        // Step 4: Create reset link
        $reset_link = "http://localhost/finemate/reset_password.php?token=" . $token;

        // Step 5: Send email (simplified)
        $subject = "Password Reset - FineMate";
        $message = "
        <html>
        <head><title>Password Reset</title></head>
        <body>
            <p>Hi {$user['name']},</p>
            <p>You requested a password reset. Click the link below to reset your password:</p>
            <p><a href='$reset_link'>$reset_link</a></p>
            <p>This link will expire in 1 hour.</p>
        </body>
        </html>";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: FineMate <no-reply@finemate.com>" . "\r\n";

        if (mail($email, $subject, $message, $headers)) {
            $_SESSION['msg'] = "Password reset link sent to your email.";
        } else {
            $_SESSION['msg'] = "Failed to send email. Please try again.";
        }

    } else {
        $_SESSION['msg'] = "Email address not found.";
    }

    header("Location: login.php");
    exit();
}
?>
