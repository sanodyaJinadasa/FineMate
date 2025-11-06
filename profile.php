<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$stmt2 = $pdo->prepare("SELECT * FROM drivers WHERE user_id = ?");
$stmt2->execute([$user_id]);
$driver = $stmt2->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FineMate | Profile</title>
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>

    <?php include 'header.php'; ?>

    <section class="profile-section">
        <div class="profile-container">

            <div class="profile-header">
                <div class="profile-img">
                    <label for="profile_image">Profile Image</label>
                    <img src="<?php echo !empty($user['image']) ? 'uploads/' . htmlspecialchars($user['image']) : 'img/default_user.png'; ?>"
                        alt="Profile" class="edit-profile-img">
                    <input type="file" name="profile_image" id="profile_image" accept="image/*">
                </div>
                <div class="profile-info">
                    <h2 class="profile-name"><?php echo htmlspecialchars($user['name']); ?></h2>
                    <p class="profile-role"><?php echo ucfirst(htmlspecialchars($user['role'])); ?></p>
                    <p class="profile-status <?php echo $user['status'] === 'active' ? 'active' : 'inactive'; ?>">
                        <?php echo ucfirst(htmlspecialchars($user['status'])); ?>
                    </p>
                    <!-- <a href="edit_profile.php" class="edit-btn"><i class="fa-solid fa-pen"></i> Edit Profile</a> -->
                </div>
            </div>

            <div class="profile-details">
                <h3 class="details-title"><i class="fa-solid fa-user"></i> User Information</h3>
                <div class="details-grid">
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
                    <p><strong>Created:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
                </div>

                <?php if ($driver): ?>
                    <h3 class="details-title"><i class="fa-solid fa-id-card"></i> Driver Information</h3>
                    <div class="details-grid">
                        <p><strong>License No:</strong> <?php echo htmlspecialchars($driver['license_no']); ?></p>
                        <p><strong>NIC:</strong> <?php echo htmlspecialchars($driver['nic']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($driver['address']); ?></p>
                        <p><strong>Contact No:</strong> <?php echo htmlspecialchars($driver['contact_no']); ?></p>
                        <p><strong>Total Points:</strong> <?php echo htmlspecialchars($driver['total_points']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($driver['status']); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

</body>

</html>