<!-- <?php
// session_start();
// if (!isset($_SESSION['user_id'])) { echo 'Login required'; exit; }
// $user_id = intval($_SESSION['user_id']);

// $pdo = new PDO("mysql:host=localhost;dbname=finemate;charset=utf8mb4", 'root', '');
// $stmt = $pdo->prepare("SELECT * FROM user_locations WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
// $stmt->execute([$user_id]);
// $loc = $stmt->fetch(PDO::FETCH_ASSOC);
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
      const lat = <?= json_encode((float) $loc['latitude']) ?>;
      const lng = <?= json_encode((float) $loc['longitude']) ?>;
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


<script>
function sendAddressToParent() {
  const address = <?= json_encode($loc ? $loc['address'] : '') ?>;
  if (window.opener && !window.opener.closed) {
    window.opener.document.getElementById('address').value = address;
    window.close();
  } else {
    alert('Parent window not found.');
  }
}
</script>

<button onclick="sendAddressToParent()" 
        style="padding:10px 15px; background:#4CAF50; color:#fff; border:none; border-radius:6px;">
  Use This Address
</button>

</html> -->




<!-- <!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Select Your Location</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 15px;
    }

    #map {
      height: 400px;
      margin-top: 10px;
      border-radius: 10px;
    }

    button {
      margin-top: 15px;
      padding: 10px 15px;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background: #0056b3;
    }
  </style>
</head>

<body>
  <h3>Detecting your current location...</h3>
  <p id="status">Please allow location access in your browser.</p>
  <div id="map"></div>

  <p><strong>Detected Address:</strong> <span id="address"></span></p>
  <button id="useAddress" style="display:none;">Use This Address</button>

  <script>
    // Initialize the map
    const map = L.map('map').setView([7.8731, 80.7718], 7); // Default Sri Lanka
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const status = document.getElementById('status');
    const addressSpan = document.getElementById('address');
    const useBtn = document.getElementById('useAddress');
    let currentAddress = '';

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(async (pos) => {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;

        status.textContent = `Latitude: ${lat.toFixed(5)}, Longitude: ${lng.toFixed(5)}`;

        map.setView([lat, lng], 15);
        const marker = L.marker([lat, lng]).addTo(map);

        try {
          const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`, {
            headers: {
              'User-Agent': 'FineMateApp/1.0 (sanodyav@gmail.com)',
              'Accept-Language': 'en'
            }
          });

          if (!res.ok) throw new Error('HTTP error ' + res.status);

          const data = await res.json();
          currentAddress = data.display_name || "Unknown location";
          addressSpan.textContent = currentAddress;
          useBtn.style.display = 'inline-block';

        } catch (err) {
          console.error('Reverse geocoding failed:', err);
          addressSpan.textContent = "Could not get address (Network or Nominatim issue).";
        }


        // Button action
        useBtn.onclick = () => {
          if (window.opener && !window.opener.closed) {
            window.opener.document.getElementById('address').value = currentAddress;
            window.close();
          } else {
            alert('Parent window not found.');
          }
        };
      }, (error) => {
        status.textContent = "Unable to get location: " + error.message;
      });
    } else {
      status.textContent = "Geolocation is not supported by this browser.";
    }
  </script>
</body>

</html> -->




<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>FineMate | Select Your Location</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 15px;
      background: #f8f9fa;
      color: #212529;
    }
    h3 {
      color: #0d6efd;
      font-weight: 600;
    }
    #map {
      height: 400px;
      margin-top: 10px;
      border-radius: 10px;
      box-shadow: 0 0 8px rgba(0,0,0,0.15);
    }
    button {
      margin-top: 15px;
      padding: 10px 18px;
      background: #0d6efd;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 15px;
    }
    button:hover {
      background: #084298;
    }
    .info {
      margin-top: 12px;
      padding: 10px;
      background: #fff;
      border-radius: 6px;
      box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }
  </style>
</head>

<body>
  <h3>Detecting your current location...</h3>
  <div id="status" class="info">Please allow location access in your browser.</div>
  <div id="map"></div>

  <div class="info">
    <p><strong>City:</strong> <span id="city">Detecting...</span></p>
    <p><strong>Address:</strong> <span id="address">Loading...</span></p>
  </div>

  <button id="useAddress" style="display:none;">Use This Address</button>

  <script>
    // Initialize Leaflet map (default: Sri Lanka)
    const map = L.map('map').setView([7.8731, 80.7718], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const statusDiv = document.getElementById('status');
    const citySpan = document.getElementById('city');
    const addressSpan = document.getElementById('address');
    const useBtn = document.getElementById('useAddress');
    let currentAddress = '';
    let currentCity = '';

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(async (pos) => {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;

        statusDiv.textContent = `Latitude: ${lat.toFixed(5)}, Longitude: ${lng.toFixed(5)}`;

        map.setView([lat, lng], 14);
        const marker = L.marker([lat, lng]).addTo(map).bindPopup("You're here").openPopup();

        try {
          // Reverse geocode using OpenStreetMap
         const res = await fetch(
            `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json&addressdetails=1`,
            {
              headers: {
                'User-Agent': 'FineMateApp/1.0 (sanodyav@gmail.com)',
                'Accept-Language': 'en'
              }
            }
          );

          if (!res.ok) throw new Error('Network response not ok');
          const data = await res.json();

          // Extract city and address
          currentAddress = data.display_name || "Unknown location";
          currentCity = data.address.city || data.address.town || data.address.village || "Unknown";

          citySpan.textContent = currentCity;
          addressSpan.textContent = currentAddress;
          useBtn.style.display = 'inline-block';

        } catch (err) {
          console.error('Reverse geocoding failed:', err);
          addressSpan.textContent = "Could not get address (Network or Nominatim issue).";
        }

        // Button action to send address back to parent window
        useBtn.onclick = () => {
          if (window.opener && !window.opener.closed) {
            if (window.opener.document.getElementById('address')) {
              window.opener.document.getElementById('address').value = currentAddress;
            }
            if (window.opener.document.getElementById('city')) {
              window.opener.document.getElementById('city').value = currentCity;
            }
            window.close();
          } else {
            alert('Parent window not found.');
          }
        };
      }, (error) => {
        statusDiv.textContent = "Unable to get location: " + error.message;
      });
    } else {
      statusDiv.textContent = "Geolocation is not supported by this browser.";
    }
  </script>
</body>
</html>
