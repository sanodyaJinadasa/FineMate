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
    $card_holder_name = $_POST['card_holder_name'];
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    if (empty($card_holder_name) || empty($card_number) || empty($expiry_date) || empty($cvv)) {
        die("Please fill in all fields.");
    }

    try {
        // Insert into payments
        $stmt =  $pdo->prepare("
            INSERT INTO payments (fine_id, user_id, card_holder_name, card_number, expiry_date, cvv, amount, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'Paid')
        ");
        $stmt->execute([$fine_id, $user_id, $card_holder_name, $card_number, $expiry_date, $cvv, $amount]);

        // Update fine payment status
        $update = $pdo->prepare("UPDATE fines SET payment_status = 'Paid' WHERE fine_id = ?");
        $update->execute([$fine_id]);

        echo "<script>
                alert('Payment successful!');
                window.location.href='view_driver_fines.php';
              </script>";

    } catch (PDOException $e) {
        echo "<script>
                alert('Payment failed: " . $e->getMessage() . "');
                window.history.back();
              </script>";
    }
}
?>
