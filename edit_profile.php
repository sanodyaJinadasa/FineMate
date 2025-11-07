<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role']; // 'driver', 'officer', or 'admin'

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// If the user is a driver, fetch extra details
$driver = null;
if ($role === 'driver') {
    $stmt = $pdo->prepare("SELECT * FROM drivers WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $driver = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="container py-5">
    <h2>Edit Profile</h2>

    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name">Full Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-control" required>
        </div>

        <?php if ($role === 'driver' && $driver): ?>
            <div class="mb-3">
                <label for="license_no">License No</label>
                <input type="text" name="license_no" value="<?php echo htmlspecialchars($driver['license_no']); ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="nic">NIC</label>
                <input type="text" name="nic" value="<?php echo htmlspecialchars($driver['nic']); ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="address">Address</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($driver['address']); ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="contact_no">Contact No</label>
                <input type="text" name="contact_no" value="<?php echo htmlspecialchars($driver['contact_no']); ?>" class="form-control" required>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="profile_image">Profile Image</label><br>
            <img src="<?php echo !empty($user['image']) ? 'uploads/' . htmlspecialchars($user['image']) : 'img/default_user.png'; ?>" 
                 alt="Profile" class="rounded mb-2" width="120" height="120"><br>
            <input type="file" name="profile_image" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
