<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'officer') {
    die("Unauthorized access.");
}

$officer_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM fines WHERE officer_id = ? ORDER BY fine_date DESC, fine_time DESC");
    $stmt->execute([$officer_id]);
    $fines = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching fines: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Added Fines</title>
    <link rel="icon" type="image/png" href="img/fine_mate_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container py-5">
        <h1 class="mb-4" style="color:white;">Fines</h1>

        <?php if (count($fines) === 0): ?>
            <div class="alert alert-info">You have not added any fines yet.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fines as $index => $fine): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
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
                                <td><a href="download_fine_pdf.php?fine_id=<?= $fine['fine_id'] ?>"
                                        class="btn btn-danger btn-sm">
                                        <i class="bi bi-file-earmark-pdf-fill"></i>PDF
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</body>

</html>