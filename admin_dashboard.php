<?php
session_start();
require 'db_connect.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

$stmt = $pdo->query("SELECT COUNT(*) AS total_messages FROM contact_messages");
$totalMessages = $stmt->fetch(PDO::FETCH_ASSOC)['total_messages'];


$stmt = $pdo->query("
    SELECT DATE(created_at) AS date, COUNT(*) AS count
    FROM contact_messages
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date ASC
");
$messageData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dates = [];
$counts = [];
foreach ($messageData as $row) {
    $dates[] = $row['date'];
    $counts[] = $row['count'];
}

$datesJson = json_encode($dates);
$countsJson = json_encode($counts);

$admin_name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin_dashboard.css">
</head>

<style>
    body {
    background: #0e1117 url('assets/img/dashboard_background.jpg') !important;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    color: var(--text-light);
    font-family: 'Inter', 'Segoe UI', sans-serif;
    min-height: 100vh;
    overflow-x: hidden;
    margin: 0;
    animation: fadeIn 0.6s ease-in-out;
}
</style>

<body>

<?php include 'admin_header.php'; ?>
    <div class="container py-5">
        <h1 class="mb-4">Admin Dashboard</h1>


        <a href="all_fines.php" class="btn btn-primary">View Chart Analysis</a><br><br>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">View All Fines</h5>
                        <p class="card-text">Check and manage all fines issued.</p>
                        <a href="admin_view_fines.php" class="btn btn-primary">View Fines</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">View Officers</h5>
                        <p class="card-text">Manage officer accounts.</p>
                        <a href="admin_view_officers.php" class="btn btn-primary">View Officers</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">View Drivers</h5>
                        <p class="card-text">Manage driver accounts.</p>
                        <a href="admin_view_drivers.php" class="btn btn-primary">View Drivers</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">View Messages</h5>
                        <p class="card-text">Manage contact messages.</p>
                        <a href="admin_view_msg.php" class="btn btn-primary">View Messages</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <?php include 'admin_footer.php'; ?>
</body>

</html>