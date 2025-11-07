<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

if (!isset($_GET['user_id'])) {
    die("Invalid request.");
}

$user_id = $_GET['user_id'];

$stmt = $pdo->prepare("
    SELECT u.user_id, u.name, u.email, u.status AS user_status,
           o.badge_no, o.station, o.contact_no, o.rank
    FROM users u
    JOIN officers o ON u.user_id = o.user_id
    WHERE u.user_id = ?
");
$stmt->execute([$user_id]);
$officer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$officer) {
    die("Officer not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $badge_no = $_POST['badge_no'];
    $station = $_POST['station'];
    $contact_no = $_POST['contact_no'];
    $rank = $_POST['rank'];
    $status = $_POST['status'];

    try {
        $pdo->beginTransaction();

        $pdo->prepare("UPDATE users SET name = ?, email = ?, status = ? WHERE user_id = ?")
            ->execute([$name, $email, $status, $user_id]);

        $pdo->prepare("UPDATE officers SET badge_no = ?, station = ?, contact_no = ?, rank = ? WHERE user_id = ?")
            ->execute([$badge_no, $station, $contact_no, $rank, $user_id]);

        $pdo->commit();
        header("Location: admin_view_officers.php?msg=Officer updated successfully");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error updating officer: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Officer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4">Edit Officer</h2>
    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($officer['name']) ?>" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($officer['email']) ?>" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Badge No</label>
            <input type="text" name="badge_no" value="<?= htmlspecialchars($officer['badge_no']) ?>" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Station</label>
            <input type="text" name="station" value="<?= htmlspecialchars($officer['station']) ?>" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Contact No</label>
            <input type="text" name="contact_no" value="<?= htmlspecialchars($officer['contact_no']) ?>" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Rank</label>
            <input type="text" name="rank" value="<?= htmlspecialchars($officer['rank']) ?>" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active" <?= $officer['user_status'] === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= $officer['user_status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="admin_view_officers.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
