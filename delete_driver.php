<?php
require 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized.");
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // delete from drivers first due to FK constraint
    $pdo->prepare("DELETE FROM drivers WHERE user_id = ?")->execute([$user_id]);
    $pdo->prepare("DELETE FROM users WHERE user_id = ?")->execute([$user_id]);

    header("Location: admin_view_drivers.php?msg=Driver+deleted+successfully");
    exit;
}
?>
