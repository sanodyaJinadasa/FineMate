<?php
// driver_register.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Driver Registration</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="assets/css/register.css">

  <!-- Load SweetAlert2 before any script that uses Swal -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

  <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="container-box d-flex">
      <div class="left-panel">
        <div class="overlay">
          <h1 class="fw-bold">Driver Registration</h1>
          <p>Securely register your account to access the Official FineMate platform. Track fines, verify records, and stay informed about your driving compliance at all times.</p>
        </div>
      </div>

      <div class="right-panel d-flex flex-column justify-content-center p-5">
        <h2 class="mb-4 fw-semibold text-center">Register</h2>

        <form action="driver_register_process.php" method="POST" novalidate>
          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
            <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" required>
          </div>

          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
          </div>

          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
          </div>

          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-card-text"></i></span>
            <input type="text" class="form-control" id="nic" name="nic" placeholder="NIC Number" required>
          </div>

          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-card-text"></i></span>
            <input type="text" class="form-control" id="license_no" name="license_no" placeholder="License Number" required>
          </div>

          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
            <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
          </div>

          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
            <input type="text" class="form-control" id="contact_no" name="contact_no" placeholder="Contact Number" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">REGISTER</button>

          <p class="text-center mt-3 mb-0 small">
            Already have an account?
            <a href="login.php" class="text-decoration-none text-primary">Login</a>
          </p>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- SweetAlert display: reads session alert and shows it -->
  <?php if (!empty($_SESSION['alert'])): 
      // Expect session alert to be array with keys: type, message, (optional) redirect
      $alert = $_SESSION['alert'];
      $type = htmlspecialchars($alert['type'] ?? 'info', ENT_QUOTES);
      $message = htmlspecialchars($alert['message'] ?? '', ENT_QUOTES);
      $redirect = isset($alert['redirect']) ? htmlspecialchars($alert['redirect'], ENT_QUOTES) : '';
      // Optionally provide timer for success messages
      $timer = ($type === 'success') ? 1800 : 0; // 1800ms for success, 0 for others
  ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: '<?php echo $type; ?>',
        title: '<?php echo ucfirst($type); ?>',
        text: '<?php echo $message; ?>',
        <?php if ($timer): ?>timer: <?php echo $timer; ?>, timerProgressBar: true,<?php endif; ?>
        confirmButtonColor: '#3085d6'
      }).then((result) => {
        <?php if ($redirect): ?>
          // If redirect is set in session, navigate there after alert closes
         // window.location.href = '<?php echo $redirect; ?>';
        <?php else: ?>
        <?php endif; ?>
      });
    });
  </script>
  <?php 
    unset($_SESSION['alert']);
    endif;
  ?>

</body>
</html>
