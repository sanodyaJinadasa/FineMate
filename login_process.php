<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

if (isset($_POST['remember'])) {
    echo "Remember me checked";
    setcookie('email', $_POST['email'], time() + (86400 * 30), "/"); 
}

echo $_POST['remember'];

// $email = trim($_POST['email'] ?? '');
// $password = $_POST['password'] ?? '';

// if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
//     die('Invalid credentials.');
// }

// try {
//     $stmt = $pdo->prepare("SELECT user_id, name, password, role, status FROM users WHERE email = ?");
//     $stmt->execute([$email]);
//     $user = $stmt->fetch();




//     if (!$user) {
//         die('No account found with this email.');
//     }

//     if ($user['status'] !== 'active') {
//         die('Account is not active. Contact admin.');
//     }

//     if (!password_verify($password, $user['password'])) {
//         die('Invalid credentials.');
//     }

//     $_SESSION['user_id'] = $user['user_id'];
//     $_SESSION['role'] = $user['role'];
//     $_SESSION['name'] = $user['name'];

//     if ($user['role'] === 'admin') {
//         header('Location: admin_dashboard.php');
//     } elseif ($user['role'] === 'officer') {
//         header('Location: home_page.php');
//     } else {
//         header('Location: home_page.php');
//     }
//     exit;
// } catch (Exception $e) {
//     die('Login error: ' . $e->getMessage());
// }
