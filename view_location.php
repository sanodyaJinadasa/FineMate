<?php
session_start();
if (!isset($_SESSION['user_id'])) { echo 'Login required'; exit; }
$user_id = intval($_SESSION['user_id']);

$pdo = new PDO("mysql:host=localhost;dbname=finemate_db;charset=utf8mb4", 'db_user', 'db_pass');
$stmt = $pdo->prepare("SELECT * FROM user_locations WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$user_id]);
$loc = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <title>Your Location</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  <style>#map { height: 400px; }</style>
</head>
<body>
  <h1>Your last known location</h1>
  <?php if ($loc): ?>
    <p><strong>City:</strong> <?= htmlspecialchars($loc['city']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($loc['address']) ?></p>
    <p><strong>Time:</strong> <?= htmlspecialchars($loc['created_at']) ?></p>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
      const lat = <?= json_encode((float)$loc['latitude']) ?>;
      const lng = <?= json_encode((float)$loc['longitude']) ?>;
      const map = L.map('map').setView([lat, lng], 13);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);
      L.marker([lat, lng]).addTo(map).bindPopup('Your last known location').openPopup();
    </script>
  <?php else: ?>
    <p>No location found.</p>
  <?php endif; ?>
</body>
</html>
