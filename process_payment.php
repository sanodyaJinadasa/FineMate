<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

define('ENCRYPTION_KEY', 'a1B2c3D4e5F6g7H8i9J0k1L2m3N4o5P6');
define('ENCRYPTION_METHOD', 'AES-256-CBC');


$data = $_POST;
file_put_contents('ipn_log.txt', print_r($data, true), FILE_APPEND);


function encryptData($data)
{
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(ENCRYPTION_METHOD));
    $encrypted = openssl_encrypt($data, ENCRYPTION_METHOD, ENCRYPTION_KEY, 0, $iv);
    return base64_encode($iv . $encrypted);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fine_id = $_POST['fine_id'];
    $amount = $_POST['amount'];
    $card_holder_name = trim($_POST['card_holder_name']);
    $card_number = trim($_POST['card_number']);
    $expiry_date = trim($_POST['expiry_date']);
    $cvv = trim($_POST['cvv']);

    if (empty($card_holder_name) || empty($card_number) || empty($expiry_date) || empty($cvv)) {
        $error_msg = "Please fill in all required fields.";
        $icon = 'error';
        $redirect = 'window.history.back();';
    } else {
        // Encrypt data
        $card_holder_name_enc = encryptData($card_holder_name);
        $card_number_enc = encryptData($card_number);
        $expiry_date_enc = encryptData($expiry_date);
        $cvv_enc = encryptData($cvv);

        try {
            // Save payment
            $stmt = $pdo->prepare("
                INSERT INTO payments (fine_id, user_id, card_holder_name, card_number, expiry_date, cvv, amount, status)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Paid')
            ");
            $stmt->execute([
                $fine_id,
                $user_id,
                $card_holder_name_enc,
                $card_number_enc,
                $expiry_date_enc,
                $cvv_enc,
                $amount
            ]);

            $update = $pdo->prepare("UPDATE fines SET payment_status = 'Paid' WHERE fine_id = ?");
            $update->execute([$fine_id]);

            $error_msg = "Payment successful! Confirmation email sent.";
            $icon = 'success';
            $redirect = "window.location.href='view_driver_fines.php';";

        } catch (PDOException $e) {
            $error_msg = "Payment failed: " . $e->getMessage();
            $icon = 'error';
            $redirect = 'window.history.back();';
        }
    }
} else {
    $error_msg = "Invalid request.";
    $icon = 'error';
    $redirect = 'window.history.back();';
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Payment Result</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: '<?= $icon ?>',
                title: '<?= $icon === "success" ? "Success" : "Oops!" ?>',
                text: '<?= addslashes($error_msg) ?>',
                confirmButtonText: 'OK'
            }).then(() => {
                <?= $redirect ?>
            });
        });
    </script>
</body>

</html>