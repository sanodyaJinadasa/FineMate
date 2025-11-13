<?php
session_start();
require 'db_connect.php';

// Only logged-in admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

$admin_name = $_SESSION['name'];

// Fetch all drivers
try {
    $stmt = $pdo->query("
        SELECT u.user_id, u.name, u.email, u.status AS user_status, u.created_at AS user_created_at, 
               d.license_no, d.nic, d.address, d.contact_no, d.total_points, 
               d.status AS driver_status, d.created_at AS driver_created_at
        FROM users u
        JOIN drivers d ON u.user_id = d.user_id
        WHERE u.role = 'driver'
        ORDER BY u.created_at DESC
    ");
    $drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching drivers: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Drivers - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin_view.css">
</head>

<body>

    <?php include 'admin_header.php'; ?>
    <div class="container py-5">
        <h1>All Drivers</h1>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="export_drivers_pdf.php" class="btn btn-success">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
        </div>

        <?php if (count($drivers) === 0): ?>
            <div class="alert alert-info">No drivers found in the system.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>License No</th>
                            <th>NIC</th>
                            <th>Address</th>
                            <th>Contact No</th>
                            <th>Total Points</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($drivers as $index => $driver): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($driver['name']) ?></td>
                                <td><?= htmlspecialchars($driver['email']) ?></td>
                                <td><?= htmlspecialchars($driver['license_no']) ?></td>
                                <td><?= htmlspecialchars($driver['nic']) ?></td>
                                <td><?= htmlspecialchars($driver['address']) ?></td>
                                <td><?= htmlspecialchars($driver['contact_no']) ?></td>
                                <td><?= htmlspecialchars($driver['total_points']) ?></td>
                                <td>
                                    <?php if ($driver['driver_status'] == 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($driver['driver_created_at']) ?></td>
                                <td>
                                    <a href="edit_driver.php?user_id=<?= $driver['user_id'] ?>"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <a href="delete_driver.php?user_id=<?= $driver['user_id'] ?>"
                                        class="btn btn-sm btn-danger swal-delete" data-id="<?= $driver['user_id'] ?>">
                                        Delete
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <?php include 'admin_footer.php'; ?>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</body>

</html>