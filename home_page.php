<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="img/fine_mate_logo.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FineMate | Traffic Fine Management System</title>
  <link rel="stylesheet" href="assets/css/home.css">
  <link rel="icon" type="image/png" href="img/fine_mate_logo.png">
</head>

<body>

  <?php include 'header.php'; ?>

  <script>
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition((position) => {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;

        // Send to Dialogflow webhook
        fetch('https://your-webhook.com/location', {
          method: 'POST',
          body: JSON.stringify({ latitude, longitude }),
          headers: { 'Content-Type': 'application/json' }
        });
      });
    } else {
      alert('Geolocation is not supported by this browser.');
    }


    const axios = require('axios');

    app.post('/location', async (req, res) => {
      const { latitude, longitude } = req.body;

      const response = await axios.get(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=YOUR_GOOGLE_API_KEY`);
      const city = response.data.results[0].address_components.find(c => c.types.includes('locality')).long_name;

      res.json({ fulfillmentText: `You are in ${city}.` });
    });

  </script>


  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // call this where appropriate (e.g., after user clicks a button or on load)
      function sendLocationToServer(latitude, longitude) {
        fetch('save_location.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ latitude, longitude })
        })
          .then(r => r.json())
          .then(data => {
            console.log('Server response:', data);
            // optionally show a toast / update UI
          })
          .catch(err => console.error('Error sending location:', err));
      }

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          position => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            sendLocationToServer(lat, lng);
          },
          error => {
            console.warn('Geolocation error', error);
          },
          { enableHighAccuracy: true, timeout: 10000 }
        );
      } else {
        alert('Geolocation is not supported by this browser.');
      }
    });
  </script>


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

  <!-- About Section -->
  <section class="about-section" id="about-section">
    <div class="about-container">
      <h2 class="about-title">About FineMate</h2>
      <p class="about-description">
        FineMate is an advanced Traffic Fine Management System designed to streamline fine handling,
        improve transparency, and make payments effortless. Our goal is to provide an efficient platform
        for both drivers and authorities to manage traffic violations in a digital, paperless environment.
      </p>
    </div>
  </section>



  <section class="cards">

    <a href="view_location.php" class="btn-secondary">View Location</a>


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


  <!-- Contact Section -->
  <section class="contact-section" id="contact-section">
    <div class="contact-container">
      <h2 class="contact-title">Contact Us</h2>
      <p class="contact-text">We’d love to hear from you! Get in touch with our team for inquiries or support.</p>

      <form class="contact-form" action="contact_process.php" method="post">
        <input type="text" name="name" placeholder="Your Name" class="contact-input" required>
        <input type="email" name="email" placeholder="Your Email" class="contact-input" required>
        <textarea name="message" rows="4" placeholder="Your Message" class="contact-textarea" required></textarea>
        <button type="submit" class="contact-btn">Send Message</button>
      </form>


      <div class="contact-info">
        <div class="contact-info-box">
          <i class="fa-solid fa-location-dot contact-icon"></i>
          <p class="contact-info-text">123 Main Street, Colombo, Sri Lanka</p>
        </div>
        <div class="contact-info-box">
          <i class="fa-solid fa-phone contact-icon"></i>
          <p class="contact-info-text">+94 77 123 4567</p>
        </div>
        <div class="contact-info-box">
          <i class="fa-solid fa-envelope contact-icon"></i>
          <p class="contact-info-text">support@finemate.lk</p>
        </div>
      </div>

    </div>
  </section>




  <?php include 'footer.php'; ?>

</body>

<?php
if (isset($_SESSION['alert'])) {
  $alert = $_SESSION['alert'];
  echo "<script>
          Swal.fire({
              icon: '{$alert['type']}',
              title: '" . ucfirst($alert['type']) . "',
              text: '{$alert['message']}',
              confirmButtonColor: '#3085d6',
          });
      </script>";
  unset($_SESSION['alert']);
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
  const userMenu = document.querySelector('.user-menu');
  const dropdown = document.querySelector('.dropdown');

  userMenu.addEventListener('click', () => {
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
  });
  window.addEventListener('click', function (e) {
    if (!userMenu.contains(e.target)) {
      dropdown.style.display = 'none';
    }
  });
</script>

</html>