<?php
require 'db_connect.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expires > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Invalid or expired token.");
    }
} else {
    die("No token provided.");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reset Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
  <form action="reset_password_process.php" method="POST" class="p-4 bg-white shadow rounded">
    <h4 class="mb-3 text-center">Reset Password</h4>
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
    <div class="mb-3">
      <label for="new_password" class="form-label">New Password</label>
      <input type="password" name="new_password" id="new_password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
  </form>
</body>
</html>
