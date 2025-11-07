<?php
session_start();
require 'db_connect.php';

// Ensure only logged-in officers can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'officer') {
    die("Unauthorized access.");
}

// Officer info from session
$officer_id = $_SESSION['user_id'];

$offender_name = trim($_POST['offender_name'] ?? '');
$offender_nic = trim($_POST['offender_nic'] ?? '');
$offender_license_no = trim($_POST['offender_license_no'] ?? '');
$vehicle_no = trim($_POST['vehicle_no'] ?? '');
$fine_type = trim($_POST['fine_type'] ?? '');
$fine_amount = trim($_POST['fine_amount'] ?? 0);
$fine_date = $_POST['fine_date'] ?? '';
$fine_time = $_POST['fine_time'] ?? '';
$fine_location = trim($_POST['fine_location'] ?? '');
$weather = trim($_POST['weather'] ?? '');
$description = trim($_POST['description'] ?? '');
$payment_status = $_POST['payment_status'] ?? 'Pending';
$due_date = $_POST['due_date'] ?? null;
$remarks = trim($_POST['remarks'] ?? '');

// Basic validation
$errors = [];
if ($offender_name === '')
    $errors[] = 'Offender name is required.';
if ($fine_type === '')
    $errors[] = 'Fine type is required.';
if ($fine_amount <= 0)
    $errors[] = 'Fine amount must be greater than 0.';
if ($fine_date === '')
    $errors[] = 'Fine date is required.';
if ($fine_time === '')
    $errors[] = 'Fine time is required.';

if (!empty($errors)) {
    die($errors[0]);
}

try {
    // Insert into fines table
    $stmt = $pdo->prepare("
        INSERT INTO fines (
            officer_id, offender_name, offender_nic,
            offender_license_no, vehicle_no, fine_type, fine_amount,
            fine_date, fine_time, fine_location, weather, description,
            payment_status, due_date, remarks
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $officer_id,
        $offender_name,
        $offender_nic,
        $offender_license_no,
        $vehicle_no,
        $fine_type,
        $fine_amount,
        $fine_date,
        $fine_time,
        $fine_location,
        $weather,
        $description,
        $payment_status,
        $due_date,
        $remarks
    ]);

     header('Location: fine_form.php?success=1');
    echo "Fine successfully recorded!";
} catch (Exception $e) {
    die("Error saving fine: " . $e->getMessage());
}
