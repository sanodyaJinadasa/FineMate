<?php
session_start();
require 'db_connect.php';

// Only logged-in admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

$admin_name = $_SESSION['name'];

// Handle activate/deactivate requests
if (isset($_GET['toggle_status'])) {
    $user_id = $_GET['toggle_status'];
    $new_status = $_GET['status'] === 'active' ? 'inactive' : 'active';
    $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE user_id = ?");
    $stmt->execute([$new_status, $user_id]);
    header("Location: admin_view_officers.php?msg=Officer status updated");
    exit;
}

try {
    // Fetch all users with role = 'officer' and join officer details
    $stmt = $pdo->query("
        SELECT u.user_id, u.name, u.email, u.status, u.created_at, 
               o.badge_no, o.station, o.contact_no, o.rank
        FROM users u
        JOIN officers o ON u.user_id = o.user_id
        WHERE u.role = 'officer'
        ORDER BY u.created_at DESC
    ");
    $officers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching officers: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Officers - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin_view_officers.css">
</head>
<body>
<?php include 'admin_header.php'; ?>
<div class="container py-5">
    <h1>All Officers</h1>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="export_officers_pdf.php" class="btn1">Export to PDF</a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <?php if (count($officers) === 0): ?>
        <div class="alert alert-info">No officers found in the system.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Badge No</th>
                        <th>Station</th>
                        <th>Contact No</th>
                        <th>Rank</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($officers as $index => $officer): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($officer['name']) ?></td>
                            <td><?= htmlspecialchars($officer['email']) ?></td>
                            <td><?= htmlspecialchars($officer['badge_no']) ?></td>
                            <td><?= htmlspecialchars($officer['station']) ?></td>
                            <td><?= htmlspecialchars($officer['contact_no']) ?></td>
                            <td><?= htmlspecialchars($officer['rank']) ?></td>
                            <td>
                                <span class="badge <?= $officer['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= htmlspecialchars($officer['status']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($officer['created_at']) ?></td>
                            <td>
                                <a href="edit_officer.php?user_id=<?= $officer['user_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete_officer.php?user_id=<?= $officer['user_id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this officer?');">
                                   Delete
                                </a>
                                <a href="?toggle_status=<?= $officer['user_id'] ?>&status=<?= $officer['status'] ?>" 
                                   class="btn btn-sm btn-<?= $officer['status'] === 'active' ? 'secondary' : 'success' ?>">
                                   <?= $officer['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
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
</body>
</html>
