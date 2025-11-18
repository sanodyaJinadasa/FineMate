<!-- 
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
</html> -->




<?php
// view_location.php
?>
<!doctype html>
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
      background: #f8f9fa;
    }

    h3 {
      color: #0d6efd;
      font-weight: 600;
    }

    #map {
      height: 400px;
      margin-top: 10px;
      border-radius: 10px;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.15);
    }

    .info {
      margin-top: 12px;
      padding: 10px;
      background: #fff;
      border-radius: 6px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
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
    // Initialize map (default Sri Lanka)
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

        map.setView([lat, lng], 15);
        const marker = L.marker([lat, lng]).addTo(map).bindPopup("You're here").openPopup();

        try {
          const res = await fetch(`reverse_geocode.php?lat=${lat}&lon=${lng}`);
          const data = await res.json();
          

          if (!data || !data.address) {
            currentAddress = "Unknown location";
            currentCity = "Unknown";
          } else {
            currentAddress = data.display_name || "Unknown location";

            currentCity = data.address.city ||
              data.address.town ||
              data.address.village ||
              data.address.hamlet ||
              data.address.state_district ||
              data.address.county ||
              data.address.state ||
              "Unknown";
          }

          citySpan.textContent = currentCity;
          addressSpan.textContent = currentAddress;
          useBtn.style.display = 'inline-block';


        } catch (err) {
          console.error('Reverse geocoding failed:', err);
          addressSpan.textContent = "Could not get address (Network or Nominatim issue).";
        }


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