<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: officer_register.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$badge_no = trim($_POST['badge_no'] ?? '');
$nic = trim($_POST['nic'] ?? '');
$address = trim($_POST['address'] ?? '');
$contact_no = trim($_POST['contact_no'] ?? '');
$station = trim($_POST['station'] ?? '');
$rank = trim($_POST['rank'] ?? '');

try {
    // Basic validation
    if ($name === '')
        throw new Exception('Name is required.');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        throw new Exception('Valid email required.');
    if (strlen($password) < 6)
        throw new Exception('Password must be at least 6 characters.');
    if ($badge_no === '')
        throw new Exception('Badge number required.');

     if (!preg_match('/^\d{10}$/', $contact_no)) {
        throw new Exception('Contact number must be exactly 10 digits.');
    }

    // NIC validation (12 or 13 digits)
    if (!preg_match('/^\d{12,13}$/', $nic)) {
        throw new Exception('NIC must be 12 or 13 digits.');
    }
    
    // Check for duplicates
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch())
        throw new Exception('Email already registered.');

    $stmt = $pdo->prepare("SELECT officer_id FROM officers WHERE badge_no = ?");
    $stmt->execute([$badge_no]);
    if ($stmt->fetch())
        throw new Exception('Badge number already registered.');

    // Insert data
    $pdo->beginTransaction();
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'officer', 'inactive')");
    $stmt->execute([$name, $email, $hashed]);
    $user_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO officers (user_id, badge_no, station, contact_no, rank, nic, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $badge_no, $station, $contact_no, $rank, $nic, $address]);

    $pdo->commit();

    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Registration successful! Wait for admin approval before logging in.'
    ];

    header('Location: officer_register.php');
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction())
        $pdo->rollBack();
    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => $e->getMessage()
    ];
    header('Location: officer_register.php');
    exit;
}
