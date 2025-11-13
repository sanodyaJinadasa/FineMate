<?php
session_start();
require 'db_connect.php';

// Only admins can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

if (!isset($_GET['user_id'])) {
    die("Invalid request.");
}

$user_id = $_GET['user_id'];

$stmt = $pdo->prepare("
    SELECT u.user_id, u.name, u.email, u.status AS user_status,
           d.license_no, d.nic, d.address, d.contact_no, d.total_points, d.status AS driver_status
    FROM users u
    JOIN drivers d ON u.user_id = d.user_id
    WHERE u.user_id = ?
");
$stmt->execute([$user_id]);
$driver = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$driver) {
    die("Driver not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name         = $_POST['name'];
    $email        = $_POST['email'];
    $license_no   = $_POST['license_no'];
    $nic          = $_POST['nic'];
    $address      = $_POST['address'];
    $contact_no   = $_POST['contact_no'];
    $total_points = $_POST['total_points'];
    $status       = $_POST['status'];

    try {
        $pdo->beginTransaction();

        // Update user table
        $updateUser = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE user_id = ?");
        $updateUser->execute([$name, $email, $user_id]);

        // Update driver table
        $updateDriver = $pdo->prepare("
            UPDATE drivers 
            SET license_no = ?, nic = ?, address = ?, contact_no = ?, total_points = ?, status = ?
            WHERE user_id = ?
        ");
        $updateDriver->execute([$license_no, $nic, $address, $contact_no, $total_points, $status, $user_id]);

        $pdo->commit();

        header("Location: admin_view_drivers.php?msg=Officer updated successfully");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error updating driver: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Driver</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'admin_header.php'; ?>
<div class="container py-5">
    <h2 class="mb-4">Edit Driver</h2>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($driver['name']) ?>" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($driver['email']) ?>" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">License Number</label>
            <input type="text" name="license_no" value="<?= htmlspecialchars($driver['license_no']) ?>" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">NIC</label>
            <input type="text" name="nic" value="<?= htmlspecialchars($driver['nic']) ?>" class="form-control" required>
        </div>

        <div class="col-md-12">
            <label class="form-label">Address</label>
            <input type="text" name="address" value="<?= htmlspecialchars($driver['address']) ?>" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact_no" value="<?= htmlspecialchars($driver['contact_no']) ?>" class="form-control" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Total Points</label>
            <input type="number" name="total_points" value="<?= htmlspecialchars($driver['total_points']) ?>" class="form-control" min="0" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="active" <?= $driver['driver_status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $driver['driver_status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="view_all_drivers.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<?php include 'admin_footer.php'; ?>
</body>
</html>
