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

  <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="container-box d-flex">
      <div class="left-panel">
        <div class="overlay">
          <h1 class="fw-bold">Official FineMate Login</h1>
          <p>Welcome back. Log in to your authorized account to securely process and track all official fine records, ensuring accountability and transparency in regulatory enforcement.</p>
        </div>
      </div>
      <div class="right-panel d-flex flex-column justify-content-center p-5">
        <h2 class="mb-4 fw-semibold text-center">Login</h2>
        <form>
          <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email address</label>
            <input type="email" class="form-control" id="email" placeholder="Enter your email">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Enter your password">
          </div>
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <input type="checkbox" id="remember">
              <label for="remember" class="small">Remember me</label>
            </div>
            <a href="#" class="text-decoration-none small text-primary">Forgot Password?</a>
          </div>
          <button type="submit" class="btn btn-primary w-100">Login</button>
          <p class="text-center mt-3 mb-0 small">Don’t have an account? <a href="#" class="text-decoration-none text-primary">Sign up</a></p>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
