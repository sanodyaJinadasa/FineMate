<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: driver_register_form.html');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$license_no = trim($_POST['license_no'] ?? '');
$nic = trim($_POST['nic'] ?? '');
$address = trim($_POST['address'] ?? '');
$contact_no = trim($_POST['contact_no'] ?? '');

$errors = [];
if ($name === '') $errors[] = 'Name is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
if ($license_no === '') $errors[] = 'License number required.';

if (!empty($errors)) {
    die($errors[0]);
}

try {
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) die('Email already registered.');

    $stmt = $pdo->prepare("SELECT driver_id FROM drivers WHERE license_no = ?");
    $stmt->execute([$license_no]);
    if ($stmt->fetch()) die('License number already registered.');

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'driver', 'active')");
    $stmt->execute([$name, $email, $hashed]);
    $user_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO drivers (user_id, license_no, nic, address, contact_no) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $license_no, $nic, $address, $contact_no]);

    $pdo->commit();

    $_SESSION['user_id'] = $user_id;
    $_SESSION['role'] = 'driver';
    $_SESSION['name'] = $name;

    header('Location: driver_dashboard.php'); 
    exit;
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    die("Registration failed: " . $e->getMessage());
}
