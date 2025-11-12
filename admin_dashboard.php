<?php
session_start();
require 'db_connect.php';

// Only logged-in admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

$stmt = $pdo->query("SELECT COUNT(*) AS total_messages FROM contact_messages");
$totalMessages = $stmt->fetch(PDO::FETCH_ASSOC)['total_messages'];

// Get messages per day (for last 7 days)
$stmt = $pdo->query("
    SELECT DATE(created_at) AS date, COUNT(*) AS count
    FROM contact_messages
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date ASC
");
$messageData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for Chart.js
$dates = [];
$counts = [];
foreach ($messageData as $row) {
    $dates[] = $row['date'];
    $counts[] = $row['count'];
}

// Convert to JSON
$datesJson = json_encode($dates);
$countsJson = json_encode($counts);

$admin_name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin_dashboard.css">
</head>

<body>
 <nav class="navbar">
    <div class="logo">
      <img src="img/fine_mate_logo.png" alt="FineMate Logo" width="40" height="40">
      <span>FineMate</span>
    </div>
    <ul>
      <li><a href="home_page.php">Home</a></li>

     <?php if (isset($_SESSION['role'])): ?>
        <?php if ($_SESSION['role'] === 'driver'): ?>
            <li><a href="view_driver_fines.php">View Fines</a></li>
        <?php elseif ($_SESSION['role'] === 'officer'): ?>
            <li><a href="view_officer_fines.php">View Fines</a></li>
            <li><a href="fine_form.php">Add Fines</a></li>
        <?php endif; ?>
    <?php endif; ?>


      <li><a href="home_page.php#about-section">About</a></li>
      <li><a href="home_page.php#contact-section">Contact</a></li>

      <li class="user-menu">
      <span class="user-icon">&#128100;</span>
      <div class="dropdown user-dropdown">
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['name'])): ?>
          <p><?php echo htmlspecialchars($_SESSION['name']); ?></p>
          <a href="profile.php">Profile</a>
          <a href="logout.php">Logout</a>
        <?php else: ?>
          <a href="login.php">Login</a>
        <?php endif; ?>
      </div>
    </li>
    </ul>
  </nav>

    <div class="container py-5">
        <h1 class="mb-4">Admin Dashboard</h1>


        <a href="all_fines.php" class="btn btn-primary">View Chart Analysis</a><br><br>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">View All Fines</h5>
                        <p class="card-text">Check and manage all fines issued.</p>
                        <a href="admin_view_fines.php" class="btn btn-primary">View Fines</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">View Officers</h5>
                        <p class="card-text">Manage officer accounts.</p>
                        <a href="admin_view_officers.php" class="btn btn-primary">View Officers</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">View Drivers</h5>
                        <p class="card-text">Manage driver accounts.</p>
                        <a href="admin_view_drivers.php" class="btn btn-primary">View Drivers</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">View Messages</h5>
                        <p class="card-text">Manage contact messages.</p>
                        <a href="admin_view_msg.php" class="btn btn-primary">View Messages</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>© 2025 FineMate System</p>
        <p>
          <a href="#">Privacy Policy</a> | 
          <a href="#">Terms of Service</a>
        </p>
    </footer>
</body>

</html>