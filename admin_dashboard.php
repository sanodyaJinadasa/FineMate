<?php
session_start();
require 'db_connect.php';

// Only logged-in admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

$admin_name = $_SESSION['name'];

// Fetch totals
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalOfficers = $pdo->query("SELECT COUNT(*) FROM users WHERE role='officer'")->fetchColumn();
$totalFines = $pdo->query("SELECT COUNT(*) FROM fines")->fetchColumn();
$totalMessages = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
      <link rel="icon" type="image/png" href="img/fine_mate_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    body {
        background: #0e1117 url('img/dashboard_background.jpg') !important;
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat ;
        background-attachment: fixed;
        color: #fff;
        font-family: 'Inter', 'Segoe UI', sans-serif;
        min-height: 100vh;
        overflow-x: hidden;
        margin: 0;
        animation: fadeIn 0.6s ease-in-out;
    }
    .card {
        background-color: rgba(30,30,30,0.85);
        border: none;
        color: #fff;
    }
    .card-title {
        font-size: 1.3rem;
    }
    .card-text {
        font-size: 1rem;
    }
</style>
<body>
<?php include 'admin_header.php'; ?>

<div class="container py-5">
    <h1 class="mb-4">Admin Dashboard</h1>
    <div class="row g-4">

        <div class="col-md-4">
            <div class="card text-center p-3">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text display-6"><?= $totalUsers ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center p-3">
                <div class="card-body">
                    <h5 class="card-title">Total Officers</h5>
                    <p class="card-text display-6"><?= $totalOfficers ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center p-3">
                <div class="card-body">
                    <h5 class="card-title">Total Fines</h5>
                    <p class="card-text display-6"><?= $totalFines ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center p-3">
                <div class="card-body">
                    <h5 class="card-title">Total Messages</h5>
                    <p class="card-text display-6"><?= $totalMessages ?></p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'admin_footer.php'; ?>
</body>
</html>
