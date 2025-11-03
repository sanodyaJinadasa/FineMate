<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'driver') {
    header('Location: login.html');
    exit;
}

// Get the logged-in user's name
$userName = $_SESSION['name'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Driver Dashboard</title>
</head>
<body>
    <h1>Driver Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($userName); ?>!</p>
</body>
</html>
