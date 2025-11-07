<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fine_id = $_POST['fine_id'];
    $amount = $_POST['amount'];
    $card_holder_name = trim($_POST['card_holder_name']);
    $card_number = trim($_POST['card_number']);
    $expiry_date = trim($_POST['expiry_date']);
    $cvv = trim($_POST['cvv']);

    if (empty($card_holder_name) || empty($card_number) || empty($expiry_date) || empty($cvv)) {
        die("Please fill in all fields.");
    }

    try {
        // Save payment
        $stmt = $pdo->prepare("
            INSERT INTO payments (fine_id, user_id, card_holder_name, card_number, expiry_date, cvv, amount, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'Paid')
        ");
        $stmt->execute([$fine_id, $user_id, $card_holder_name, $card_number, $expiry_date, $cvv, $amount]);

        // Update fine status
        $update = $pdo->prepare("UPDATE fines SET payment_status = 'Paid' WHERE fine_id = ?");
        $update->execute([$fine_id]);

        // Fetch offender email
        $userStmt = $pdo->prepare("SELECT name, email FROM users WHERE user_id = ?");
        $userStmt->execute([$user_id]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

        if ($user && !empty($user['email'])) {
            $to = $user['email'];
            $subject = "Payment Confirmation - Fine ID #$fine_id";
            $message = "
            <html>
            <head><title>Fine Payment Confirmation</title></head>
            <body style='font-family: Arial, sans-serif;'>
                <h2 style='color:#2b7cff;'>Fine Payment Successful</h2>
                <p>Dear " . htmlspecialchars($user['name']) . ",</p>
                <p>Your payment for <strong>Fine ID #$fine_id</strong> has been successfully received.</p>
                <p><strong>Amount Paid:</strong> Rs. " . htmlspecialchars($amount) . ".00</p>
                <p>Payment Status: <span style='color:green;'>Paid</span></p>
                <hr>
                <p>Thank you for settling your fine promptly.</p>
                <p>— FineMate System</p>
            </body>
            </html>";

            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8\r\n";
            $headers .= "From: FineMate <no-reply@finemate.com>\r\n";

            // Send email
            @mail($to, $subject, $message, $headers);
        }

        echo "<script>
                alert('Payment successful! Confirmation email sent.');
                window.location.href='view_driver_fines.php';
              </script>";

    } catch (PDOException $e) {
        echo "<script>
                alert('Payment failed: " . addslashes($e->getMessage()) . "');
                window.history.back();
              </script>";
    }
}
?>
