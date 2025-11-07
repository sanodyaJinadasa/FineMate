<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$name = $_POST['name'];
$email = $_POST['email'];
$imageName = null;

// Handle image upload
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['profile_image']['tmp_name'];
    $fileName = $_FILES['profile_image']['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileExt, $allowed)) {
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        $newFileName = uniqid('user_', true) . '.' . $fileExt;
        $uploadPath = 'uploads/' . $newFileName;

        if (move_uploaded_file($fileTmp, $uploadPath)) {
            $imageName = $newFileName;
        }
    }
}

try {
    $pdo->beginTransaction();

    // === Update common user info ===
    if ($imageName) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, image = ? WHERE user_id = ?");
        $stmt->execute([$name, $email, $imageName, $user_id]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE user_id = ?");
        $stmt->execute([$name, $email, $user_id]);
    }

    // === Update driver-specific info if applicable ===
    if ($role === 'driver') {
        $license_no = $_POST['license_no'];
        $nic = $_POST['nic'];
        $address = $_POST['address'];
        $contact_no = $_POST['contact_no'];

        $stmt = $pdo->prepare("UPDATE drivers SET license_no = ?, nic = ?, address = ?, contact_no = ? WHERE user_id = ?");
        $stmt->execute([$license_no, $nic, $address, $contact_no, $user_id]);
    }

    $pdo->commit();

    echo "<script>
        alert('Profile updated successfully!');
        window.location.href = 'profile.php';
    </script>";

} catch (PDOException $e) {
    $pdo->rollBack();
    echo "<script>
        alert('Error updating profile: " . addslashes($e->getMessage()) . "');
        window.history.back();
    </script>";
}
?>
