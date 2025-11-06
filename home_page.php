<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'driver') {
    header('Location: login.php');
    exit;
}

$userName = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FineMate | Traffic Fine Management System</title>
  <style>
  body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  color: #222;
  scroll-behavior: smooth;
  background: #0e1117 url('img/home_bg1.jpg');
  background-size: cover;        
  background-position: center;   
  background-repeat: no-repeat;  
  background-attachment: fixed;  
}

    a {
      text-decoration: none;
      color: inherit;
    }

    img {
      max-width: 100%;
      display: block;
    }

.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: transparent;
  backdrop-filter: blur(12px);
  color: #ffffffff;
  padding: 15px 60px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
  position: sticky;
  top: 0;
  z-index: 1000;
  transition: all 0.3s ease;
}

.navbar.scrolled {
  background: rgba(30, 77, 160, 0.95);
  backdrop-filter: blur(8px);
  padding: 10px 50px;
}

.navbar .logo {
  display: flex;
  align-items: center;
  font-family: 'Poppins', sans-serif; 
  font-size: 28px;
  font-weight: 700;
  letter-spacing: 1.5px;
  color: #ffffffff;
  cursor: pointer;
  transition: transform 0.4s ease, color 0.3s ease;
  position: relative;
}

.navbar .logo:hover {
  transform: scale(1.05);
  color: #00e5ffff;
}

.navbar .logo:hover::after {
  width: 100%;
}


.navbar .logo:hover {
  transform: scale(1.05);
}

.navbar .logo img {
  height: 40px;
  width: 40px;
  border-radius: 50%;
  margin-right: 10px;
  transition: transform 0.3s ease;
}

.navbar .logo img:hover {
  transform: rotate(8deg);
}

.navbar ul {
  list-style: none;
  display: flex;
  gap: 35px;
  margin: 0;
  padding: 0;
}

.navbar ul li {
  position: relative;
}

.navbar ul li a {
  display: flex;
  align-items: center;
  font-family: 'Poppins', sans-serif; /* clean, professional font */
  font-size: 20px;
  font-weight: 700;
  letter-spacing: 1.5px;
  color: #ffffffff;
  cursor: pointer;
  transition: transform 0.4s ease, color 0.3s ease;
  position: relative;
}

.navbar ul li a::after {
width: 100%;
}

.navbar ul li a:hover {
  transform: scale(1.05);
  color: #00e5ffff;
}

@media (max-width: 992px) {
  .navbar {
    flex-direction: column;
    align-items: flex-start;
    padding: 15px 30px;
  }

  .navbar ul {
    flex-direction: column;
    gap: 15px;
    width: 100%;
    background: rgba(44, 100, 198, 0.95);
    border-radius: 12px;
    padding: 15px 20px;
    margin-top: 10px;
    display: none;
  }

  .navbar.active ul {
    display: flex;
  }

  .navbar .menu-toggle {
    display: block;
    cursor: pointer;
    font-size: 26px;
  }
}

@media (min-width: 993px) {
  .navbar .menu-toggle {
    display: none;
  }
}

.menu-toggle {
  color: #fff;
  font-size: 28px;
  cursor: pointer;
  transition: transform 0.3s ease;
}

.menu-toggle:hover {
  transform: rotate(90deg);
}

.navbar .btn-login {
  background: #ffe15d;
  color: #1e4da0;
  padding: 8px 18px;
  border-radius: 25px;
  font-weight: 600;
  transition: all 0.3s ease;
  text-decoration: none;
}

.navbar .btn-login:hover {
  background: #fff;
  color: #1e4da0;
  box-shadow: 0 4px 10px rgba(255, 225, 93, 0.4);
}

.hero {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 100px 120px;
  background: transparent;
  position: relative;
}


.hero::before {
  width: 400px;
  height: 400px;
  background: rgba(44, 100, 198, 0.1);
  top: -100px;
  right: -150px;
}

.hero::after {
  width: 300px;
  height: 300px;
  background: rgba(255, 225, 93, 0.2);
  bottom: -80px;
  left: -100px;
}

.hero-text {
  max-width: 50%;
  z-index: 1;
  animation: fadeInLeft 1s ease;
}

.hero-text h1 {
  font-family: 'Poppins', sans-serif; 
  font-size: 54px;
  font-weight: 700;
  line-height: 1.2;
  letter-spacing: 0.8px;
  color: #ffffffff;
  margin-bottom: 24px;
  text-align: center;
  text-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); 
  transition: transform 0.4s ease, text-shadow 0.4s ease;
}

.hero-text h1 span {
  background: linear-gradient(90deg, #1e4da0, #2c64c6, #3e82ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  display: inline-block;
  position: relative;
}

.hero-text h1:hover {
  transform: scale(1.03);
  text-shadow: 0 2px 6px rgba(0, 255, 255, 1);
}

.hero-text h1 span::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  height: 3px;
  width: 0%;
  background: linear-gradient(90deg, #1e4da0, #3e82ff);
  border-radius: 2px;
  transition: width 0.5s ease;
}

.hero-text h1:hover span::after {
  width: 100%;
}

.hero-text p {
  font-size: 18px;
  color: #ffffffff;
  margin-bottom: 35px;
  line-height: 1.7;
  max-width: 90%;
}

.btn-primary {
  font-family: 'Poppins', sans-serif;
   background: linear-gradient(
         rgba(56, 56, 56, 0.7),
         rgba(24, 24, 24, 0.7)
      );
  color: #fff;
  padding: 14px 40px;
  margin-left:30%;
  border-radius: 10px;
  font-weight: 600;
  letter-spacing: 0.5px;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 0 0 rgba(44, 100, 198, 0);
}

.btn-primary:hover {
  background-color: #2c64c6;
  box-shadow: 0 0 20px rgba(44, 100, 198, 0.5);
  transform: translateY(-3px);
}

.btn-primary:active {
  transform: scale(0.97);
  box-shadow: 0 0 10px rgba(44, 100, 198, 0.3);
}

.hero-image {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1;
  padding: 40px 0;
  overflow: hidden;
  background: transparent;
}

.hero-image img {
  width: 600px;
  height: 450px;
  border-radius: 20px;
  animation: fadeInRight 1s ease;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  transition: transform 0.4s ease, box-shadow 0.4s ease, filter 0.4s ease;
  object-fit: cover;
  cursor: pointer;
}

.hero-image img:hover {
  transform: scale(1.05);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
  filter: brightness(1.05);
}

@keyframes fadeInRight {
  from {
    opacity: 0;
    transform: translateX(50px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes floatImage {
  0% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
  100% {
    transform: translateY(0);
  }
}

.hero-image img {
  animation: fadeInRight 1s ease, floatImage 5s ease-in-out infinite;
}

@media (max-width: 768px) {
  .hero-image img {
    width: 90%;
    height: auto;
    border-radius: 15px;
  }
}

@media (max-width: 480px) {
  .hero-image {
    padding: 20px 0;
  }

  .hero-image img {
    width: 100%;
    border-radius: 10px;
  }
}

.hero-image::after {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  background: rgba(0, 0, 0, 0.6);
  color: #fff;
  padding: 8px 20px;
  border-radius: 25px;
  font-size: 16px;
  letter-spacing: 0.5px;
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
}

.hero-image:hover::after {
  opacity: 1;
}

@keyframes fadeInLeft {
  from {
    opacity: 0;
    transform: translateX(-50px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@keyframes fadeInRight {
  from {
    opacity: 0;
    transform: translateX(50px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

@media (max-width: 992px) {
  .hero {
    flex-direction: column-reverse;
    text-align: center;
    padding: 70px 30px;
  }

  .hero-text {
    max-width: 100%;
    animation: fadeInUp 1s ease;
  }

  .hero-text h1 {
    font-size: 36px;
  }

  .hero-text p {
    font-size: 16px;
    max-width: 100%;
  }

  .hero-image img {
    width: 320px;
    margin-bottom: 30px;
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.cards {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  gap: 40px;
  padding: 100px 60px;
  background: transparent;
  flex-wrap: wrap;
  position: relative;
  overflow: hidden;
}

.cards::before {
  width: 500px;
  height: 500px;
  background: rgba(44, 100, 198, 0.07);
  top: -150px;
  left: -120px;
}

.cards::after {
  width: 350px;
  height: 350px;
  background: rgba(255, 225, 93, 0.1);
  bottom: -100px;
  right: -100px;
}

.card {
  background: #d0d0d0ff;
  padding: 50px 30px 40px;
  border-radius: 20px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
  width: 300px;
  text-align: center;
  transition: all 0.35s ease;
  position: relative;
  overflow: hidden;
  z-index: 1;
}

.card::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 5px;
  background-color: #00fff7ff;
  box-shadow: 0 0 10px #00bcd4;
  animation: glow 1.5s ease-in-out infinite alternate;
}

@keyframes glow {
  from {
    box-shadow: 0 0 5px #00bcd4;
  }
  to {
    box-shadow: 0 0 20px #00bcd4;
  }
}


.card:hover::before {
  height: 100%;
  opacity: 0.05;
}

.card:hover {
  transform: translateY(-12px) scale(1.02);
  box-shadow: 0 14px 30px rgba(0, 0, 0, 0.15);
}

.card img {
  width: 90px;
  margin-bottom: 20px;
  filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.15));
  transition: transform 0.4s ease;
  margin-left: auto;
  margin-right: auto;
  display: flex;
}

.card:hover img {
  transform: scale(1.1) rotate(3deg);
}

.card h3 {
  color: #1b3a75;
  margin-bottom: 15px;
  font-size: 22px;
  font-weight: 700;
  transition: color 0.3s ease;
}

.card:hover h3 {
  color: #2c64c6;
}

.card p {
  color: #3f3c3cff;
  line-height: 1.7;
  font-size: 16px;
  margin-bottom: 0;
}

.card .btn-learn {
  display: inline-block;
  margin-top: 20px;
  background-color: #2c64c6;
  color: #fff;
  padding: 10px 22px;
  border-radius: 25px;
  font-weight: 600;
  font-size: 15px;
  text-decoration: none;
  transition: all 0.3s ease;
}

.card .btn-learn:hover {
  background-color: #1e4da0;
  transform: translateY(-3px);
}

@media (max-width: 1024px) {
  .cards {
    gap: 30px;
    padding: 80px 40px;
  }

  .card {
    width: 280px;
  }
}

@media (max-width: 768px) {
  .cards {
    flex-direction: column;
    align-items: center;
    padding: 60px 20px;
  }

  .card {
    width: 90%;
    max-width: 350px;
  }
}

    footer {
      background: linear-gradient(
         rgba(56, 56, 56, 0.8),
         rgba(0, 0, 0, 0.61)
      );

      color: #fff;
      text-align: center;
      padding: 25px 10px;
      margin-top: 40px;
    }

    footer a {
      color: #ffe15d;
      margin: 0 8px;
    }

    footer a:hover {
      text-decoration: underline;
    }

    @keyframes fadeInLeft {
      from { opacity: 0; transform: translateX(-50px); }
      to { opacity: 1; transform: translateX(0); }
    }

    @keyframes fadeInRight {
      from { opacity: 0; transform: translateX(50px); }
      to { opacity: 1; transform: translateX(0); }
    }
  </style>
</head>

<body>

  <nav class="navbar">
    <div class="logo">
      <img src="img/fine_mate_logo.png" alt="FineMate Logo" width="40" height="40">
      <span>FineMate</span>
       <?php echo htmlspecialchars($userName); ?>
    </div>
    <ul>
      <li><a href="#">Home</a></li>
      <li><a href="#">About</a></li>
      <li><a href="#">Contact</a></li>
     <?php if (isset($_SESSION['user_id'])): ?>
        <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
        <li><a href="login.php">User Login</a></li>
    <?php endif; ?>
      <li><a href="login.php">Officer Login</a></li>
    </ul>
  </nav>

  <section class="hero">
    <div class="hero-text">
      <h1>Traffic Fine Management System</h1>
      <p>Efficiently manage traffic fines, driver records, and payments with our web-based system.</p>
      <a href="#" class="btn-primary">Get Started</a>
    </div>
    <div class="hero-image">
      <img src="img/home_page.jpg" alt="Car Fine Icon">
    </div>
  </section>

  <section class="cards">
    <div class="card">
      <img src="img/fine_history_icon.jpg" alt="Fine History Icon">
      <h3>Fine History</h3>
      <p>View and track all your previous traffic violations and fines easily in one place.</p>
    </div>

    <div class="card">
      <img src="img/payment_processing_icon.jpg" alt="Payment Processing Icon">
      <h3>Payment Processing</h3>
      <p>Make online fine payments securely and receive instant digital receipts.</p>
    </div>

    <div class="card">
      <img src="img/technical_support_icon.jpg" alt="Support Icon">
      <h3>Support Center</h3>
      <p>Our support team for help regarding fines or payment issues anytime.</p>
    </div>
  </section>

  <footer>
    <p>© 2025 FineMate System</p>
    <p>
      <a href="#">Privacy Policy</a> | 
      <a href="#">Terms of Service</a>
    </p>
  </footer>

</body>
</html>
