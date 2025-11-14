<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Officer Registration</title>
<link rel="icon" type="image/png" href="img/fine_mate_logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="assets/css/officer_reg.css">

  <!-- ✅ Include SweetAlert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="container-box d-flex">
      <div class="left-panel">
        <div class="overlay">
          <h1 class="fw-bold">Officer Registration</h1>
          <p>Join the FineMate enforcement system by registering your officer account.</p>
        </div>
      </div>

      <div class="right-panel d-flex flex-column justify-content-center p-5">
        <h2 class="mb-4 fw-semibold text-center">Register</h2>

        <form action="officer_register_process.php" method="POST">
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
            <span class="input-group-text"><i class="bi bi-award-fill"></i></span>
            <input type="text" class="form-control" id="badge_no" name="badge_no" placeholder="Badge Number" required>
          </div>

          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-card-text"></i></span>
            <input type="text" class="form-control" id="nic" name="nic" placeholder="NIC Number" required>
          </div>

          <!-- <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
            <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
          </div> -->

          
          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-geo-alt-fill"></i></span>
            <input type="text" class="form-control" id="address" name="address" placeholder="Address" required>
            <a href="view_location.php" class="btn btn-secondary"
              onclick="window.open('view_location.php', 'selectLocation', 'width=800,height=600'); return false;">
              Select Location
            </a>
          </div>

          
          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
            <input type="text" class="form-control" id="contact_no" name="contact_no" placeholder="Contact Number" required>
          </div>

          <div class="mb-3 input-group">
            <span class="input-group-text"><i class="bi bi-building"></i></span>
            <input type="text" class="form-control" id="station" name="station" placeholder="Police Station" required>
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

  <?php
  session_start();
  if (isset($_SESSION['alert'])) {
      $alert = $_SESSION['alert'];
      echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: '{$alert['type']}',
            title: '".ucfirst($alert['type'])."',
            text: '{$alert['message']}',
            confirmButtonColor: '#3085d6'
          });
        });
      </script>";
      unset($_SESSION['alert']);
  }
  ?>
</body>
</html>
