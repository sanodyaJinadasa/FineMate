<?php
// driver_register_process.php
session_start();
require 'db_connect.php'; // must provide $pdo (PDO) and use exceptions

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: driver_register.php');
    exit;
}

// Collect input (trim where appropriate)
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$license_no = trim($_POST['license_no'] ?? '');
$nic = trim($_POST['nic'] ?? '');
$address = trim($_POST['address'] ?? '');
$contact_no = trim($_POST['contact_no'] ?? '');

// Helper to set session alert and redirect back to form
function set_alert_and_redirect($type, $message, $redirect = 'driver_register.php') {
    $_SESSION['alert'] = [
        'type' => $type,
        'message' => $message,
    ];
    if ($redirect) $_SESSION['alert']['redirect'] = $redirect;
    header('Location: driver_register.php');
    exit;
}

// Basic validation
if ($name === '') {
    set_alert_and_redirect('error', 'Name is required.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_alert_and_redirect('error', 'Valid email is required.');
}
if (strlen($password) < 6) {
    set_alert_and_redirect('error', 'Password must be at least 6 characters.');
}
if ($license_no === '') {
    set_alert_and_redirect('error', 'License number is required.');
}


if ($nic === '') {
    set_alert_and_redirect('error', 'NIC is required.');
}

if (!preg_match('/^\d{12,13}$/', $nic)) {
    set_alert_and_redirect('error', 'NIC must be 12 or 13 digits.');
}

if ($address === '') {
    set_alert_and_redirect('error', 'Address is required.');
}

if ($contact_no === '') {
    set_alert_and_redirect('error', 'Contact number is required.');
}

if (!preg_match('/^\d{10}$/', $contact_no)) {
    set_alert_and_redirect('error', 'Contact number must be exactly 10 digits.');
}


try {
    // Check for existing email
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        set_alert_and_redirect('error', 'Email already registered.');
    }

    // Check for existing license number
    $stmt = $pdo->prepare("SELECT driver_id FROM drivers WHERE license_no = ?");
    $stmt->execute([$license_no]);
    if ($stmt->fetch()) {
        set_alert_and_redirect('error', 'License number already registered.');
    }

    // All good — insert within a transaction
    $pdo->beginTransaction();

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'driver', 'active')");
    $stmt->execute([$name, $email, $hashed]);
    $user_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO drivers (user_id, license_no, nic, address, contact_no) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $license_no, $nic, $address, $contact_no]);

    $pdo->commit();

    // Set session for logged-in user if you want them auto-logged-in
    $_SESSION['user_id'] = $user_id;
    $_SESSION['role'] = 'driver';
    $_SESSION['name'] = $name;

    // Success alert — redirect to home_page.php after showing alert on the form page
    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => "Registration successful!",
        // We'll instruct the form page to redirect to home_page.php after the alert
        'redirect' => 'home_page.php'
    ];

    // Redirect back to form page which shows SweetAlert then navigates
    header('Location: driver_register.php');
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    // Log the error server-side if you have logging; do not expose raw SQL errors to users in production
    $msg = 'Registration failed: ' . $e->getMessage();
    set_alert_and_redirect('error', $msg);
}
