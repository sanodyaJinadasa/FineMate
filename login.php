<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <!-- comment -->

  <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="container-box d-flex">
      <div class="left-panel">
        <div class="overlay">
          <h1 class="fw-bold">Official FineMate Login</h1>
          <p>Welcome back. Log in to your authorized account to securely process and track all official fine records,
            ensuring accountability and transparency in regulatory enforcement.</p>
        </div>
      </div>
      <div class="right-panel d-flex flex-column justify-content-center p-5">
        <h2 class="mb-4 fw-semibold text-center">Login</h2>
        <form action="login_process.php" method="POST">

          <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
          </div>
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <input type="checkbox" id="remember" name="remember">
              <label for="remember" class="small">Remember me</label>
            </div>
            <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal"
              class="text-decoration-none small text-primary">
              Forgot Password?
            </a>
          </div>
          <button type="submit" class="btn btn-primary w-100">Login</button>
          <div class="text-center mt-4">
            <span class="small">
              <a href="driver_register.php" class="text-decoration-none fw-semibold text-primary mx-3">
                Driver Sign Up
              </a>
              |
              <a href="officer_register.php" class="text-decoration-none fw-semibold text-primary mx-3">
                Officer Sign Up
              </a>
            </span>
          </div>

        </form>
      </div>
    </div>
  </div>

<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="forgotPasswordModalLabel">Reset Password</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="forgot_password_process.php" method="POST">
          <div class="modal-body">
            <p class="small text-muted mb-3">Enter your registered email address and we’ll send you a link to reset your password.</p>
            <div class="mb-3">
              <label for="resetEmail" class="form-label fw-semibold">Email address</label>
              <input type="email" class="form-control" id="resetEmail" name="resetEmail" placeholder="Enter your email" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>