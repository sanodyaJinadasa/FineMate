<?php
session_start();
require 'db_connect.php';

// Only logged-in admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

$admin_name = $_SESSION['name'];

try {
    // Fetch all users with role = 'officer' and join officer details
    $stmt = $pdo->query("
        SELECT u.user_id, u.name, u.email, u.status, u.created_at, o.badge_no, o.station, o.contact_no, o.rank
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
</head>
<body>
<div class="container py-5">
    <h1 class="mb-4">All Officers</h1>

    <?php if (count($officers) === 0): ?>
        <div class="alert alert-info">No officers found in the system.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
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
                            <td><?= htmlspecialchars($officer['status']) ?></td>
                            <td><?= htmlspecialchars($officer['created_at']) ?></td>
                            <td>
                                <a href="edit_officer.php?user_id=<?= $officer['user_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete_officer.php?user_id=<?= $officer['user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this officer?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
