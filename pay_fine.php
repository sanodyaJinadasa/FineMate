<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$fine_id = $_GET['fine_id'] ?? null;

if (!$fine_id) {
    die("Invalid fine ID.");
}

$stmt = $pdo->prepare("SELECT * FROM fines WHERE fine_id = ?");
$stmt->execute([$fine_id]);
$fine = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fine) {
    die("Fine not found.");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Pay Fine</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-lg">



            <!-- <form method="post" action="https://sandbox.payhere.lk/pay/checkout">
    <input type="hidden" name="merchant_id" value="YOUR_MERCHANT_ID">
    <input type="hidden" name="return_url" value="http://yourwebsite.com/payment-success.php">
    <input type="hidden" name="cancel_url" value="http://yourwebsite.com/payment-cancel.php">
    <input type="hidden" name="notify_url" value="http://yourwebsite.com/ipn.php">

    <input type="hidden" name="order_id" value="ORDER1234">
    <input type="hidden" name="items" value="Test Item">
    <input type="hidden" name="currency" value="LKR">
    <input type="hidden" name="amount" value="100.00">

    <input type="submit" value="Pay Now">
</form> -->




            <div class="card-header bg-primary text-white">
                <h4>Pay Fine - #<?= htmlspecialchars($fine['fine_id']) ?></h4>
            </div>
            <div class="card-body">
                <p><strong>Fine Reason:</strong> <?= htmlspecialchars($fine['fine_type']) ?></p>
                <p><strong>Amount:</strong> Rs. <?= number_format($fine['fine_amount'], 2) ?></p>

                <form action="process_payment.php" method="POST">
                    <input type="hidden" name="fine_id" value="<?= $fine['fine_id'] ?>">
                    <input type="hidden" name="amount" value="<?= $fine['fine_amount'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Card Holder Name</label>
                        <input type="text" name="card_holder_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Card Number</label>
                        <input type="text" name="card_number" maxlength="16" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expiry Date (MM/YY)</label>
                            <input type="text" name="expiry_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">CVV</label>
                            <input type="password" name="cvv" maxlength="4" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Pay Now</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>