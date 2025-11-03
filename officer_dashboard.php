<?php
session_start();

// Check if user is logged in and is an officer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'officer') {
    header('Location: login.html');
    exit;
}

// Get the logged-in officer's name
$userName = $_SESSION['name'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Officer Dashboard</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <h1>Officer Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($userName); ?>!</p>
    <a href="fine_form.php">Add Fine</a>

</body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: 'Fine has been added successfully.',
        confirmButtonColor: '#198754'
    });
</script>
<?php endif; ?>
</html>
