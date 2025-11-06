  <link rel="stylesheet" href="assets/css/home.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

 <nav class="navbar">
    <div class="logo">
      <img src="img/fine_mate_logo.png" alt="FineMate Logo" width="40" height="40">
      <span>FineMate</span>
    </div>
    <ul>
      <li><a href="home_page.php">Home</a></li>

     <?php if (isset($_SESSION['role'])): ?>
        <?php if ($_SESSION['role'] === 'driver'): ?>
            <li><a href="view_driver_fines.php">View Fines</a></li>
        <?php elseif ($_SESSION['role'] === 'officer'): ?>
            <li><a href="view_officer_fines.php">View Fines</a></li>
            <li><a href="fine_form.php">Add Fines</a></li>
        <?php endif; ?>
    <?php endif; ?>


      <li><a href="home_page.php#about-section">About</a></li>
      <li><a href="home_page.php#contact-section">Contact</a></li>

      <li class="user-menu">
      <span class="user-icon">&#128100;</span>
      <div class="dropdown user-dropdown">
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['name'])): ?>
          <p><?php echo htmlspecialchars($_SESSION['name']); ?></p>
          <a href="profile.php">Profile</a>
          <a href="logout.php">Logout</a>
        <?php else: ?>
          <a href="login.php">Login</a>
        <?php endif; ?>
      </div>
    </li>
    </ul>
  </nav>