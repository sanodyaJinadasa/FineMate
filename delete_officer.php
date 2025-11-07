<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

if (!isset($_GET['user_id'])) {
    die("Invalid request.");
}

$user_id = $_GET['user_id'];

try {
    $pdo->beginTransaction();

    $pdo->prepare("DELETE FROM officers WHERE user_id = ?")->execute([$user_id]);
    $pdo->prepare("DELETE FROM users WHERE user_id = ?")->execute([$user_id]);

    $pdo->commit();

    header("Location: admin_view_officers.php?msg=Officer deleted successfully");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    die("Error deleting officer: " . $e->getMessage());
}
?>
