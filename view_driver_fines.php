<?php
session_start();
require 'db_connect.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'driver') {
    header('Location: login.php');
    exit;
}

$driver_user_id = $_SESSION['user_id'];


try {
    $stmt = $pdo->prepare("SELECT nic FROM drivers WHERE user_id = ?");
    $stmt->execute([$driver_user_id]);
    $driver = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$driver) {
        die("Driver info not found.");
    }

    $driver_nic = $driver['nic'];

    // Get all fines for this driver
    $stmt = $pdo->prepare("SELECT * FROM fines WHERE offender_nic = ? ORDER BY fine_date DESC, fine_time DESC");
    $stmt->execute([$driver_nic]);
    $fines = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die("Error fetching fines: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Fines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container py-5">
        <h1 class="mb-4">My Fines</h1>

        <?php if (count($fines) === 0): ?>
            <div class="alert alert-info">No fines found for your NIC.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
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
      <?php include 'footer.php'; ?>
</body>

</html>