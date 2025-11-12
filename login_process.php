<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (isset($_POST['remember'])) {
    setcookie("user_email", $email, time() + (86400 * 30), "/"); 
    setcookie("user_password", $password, time() + (86400 * 30), "/");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    $_SESSION['alert'] = ['type' => 'error', 'message' => 'Invalid credentials.'];
    header('Location: login.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT user_id, name, password, role, status FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'No account found with this email.'];
        header('Location: login.php');
        exit;
    }

    if ($user['status'] !== 'active') {
        $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Account is not active. Contact admin.'];
        header('Location: login.php');
        exit;
    }

    if (!password_verify($password, $user['password'])) {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Invalid credentials.'];
        header('Location: login.php');
        exit;
    }

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['name'] = $user['name'];

    if ($user['role'] === 'admin') {
        header('Location: admin_dashboard.php');
    } elseif ($user['role'] === 'officer') {
        header('Location: home_page.php');
    } else {
        header('Location: home_page.php');
    }
    exit;

} catch (Exception $e) {
    $_SESSION['alert'] = ['type' => 'error', 'message' => 'Login error: ' . $e->getMessage()];
    header('Location: login.php');
    exit;
}
?>
