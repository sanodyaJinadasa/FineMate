<?php
session_start();
require 'db_connect.php';

// Only logged-in admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

$admin_name = $_SESSION['name'];

try {
    // Fetch all fines ordered by date & time
    $stmt = $pdo->query("SELECT * FROM fines ORDER BY fine_date DESC, fine_time DESC");
    $fines = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching fines: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Fines - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h1 class="mb-4">All Fines</h1>

    <?php if (count($fines) === 0): ?>
        <div class="alert alert-info">No fines found in the system.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Officer Name</th>
                        <th>Offender Name</th>
                        <th>NIC</th>
                        <th>License No</th>
                        <th>Vehicle No</th>
                        <th>Fine Type</th>
                        <th>Amount (Rs)</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Weather</th>
                        <th>Payment Status</th>
                        <th>Due Date</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fines as $index => $fine): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($fine['officer_id']) ?></td>
                            <td><?= htmlspecialchars($fine['offender_name']) ?></td>
                            <td><?= htmlspecialchars($fine['offender_nic']) ?></td>
                            <td><?= htmlspecialchars($fine['offender_license_no']) ?></td>
                            <td><?= htmlspecialchars($fine['vehicle_no']) ?></td>
                            <td><?= htmlspecialchars($fine['fine_type']) ?></td>
                            <td><?= number_format($fine['fine_amount'], 2) ?></td>
                            <td><?= htmlspecialchars($fine['fine_date']) ?></td>
                            <td><?= htmlspecialchars($fine['fine_time']) ?></td>
                            <td><?= htmlspecialchars($fine['fine_location']) ?></td>
                            <td><?= htmlspecialchars($fine['weather']) ?></td>
                            <td><?= htmlspecialchars($fine['payment_status']) ?></td>
                            <td><?= htmlspecialchars($fine['due_date']) ?></td>
                            <td><?= htmlspecialchars($fine['remarks']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
