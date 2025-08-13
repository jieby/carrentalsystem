<?php
if (!isset($_GET['car_id'])) {
    die("Missing car ID.");
}
$car_id = intval($_GET['car_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Start GPS Tracking</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="output.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
  <div class="bg-white p-6 rounded shadow max-w-md text-center">
    <h1 class="text-xl font-bold mb-4">Car Tracking (ID: <?= $car_id ?>)</h1>
    <button onclick="toggleTracking()" id="trackBtn" class="bg-blue-600 text-white px-4 py-2 rounded">
      Start Tracking
    </button>
    <p id="statusText" class="mt-4 text-sm text-gray-600"></p>
  </div>

  <script>
    let watchId = null;
    const carId = <?= $car_id ?>;

    async function sendLocation(lat, lng) {
      try {
        const res = await fetch('update_location.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `car_id=${carId}&latitude=${lat}&longitude=${lng}`
        });
        const data = await res.json();
        return data.status === 'success';
      } catch (e) {
        console.warn('Failed to send location', e);
        return false;
      }
    }

    function toggleTracking() {
      const btn = document.getElementById('trackBtn');
      const status = document.getElementById('statusText');

      if (watchId !== null) {
        navigator.geolocation.clearWatch(watchId);
        watchId = null;
        btn.textContent = 'Start Tracking';
        status.textContent = 'Tracking stopped.';
        return;
      }

      if (!navigator.geolocation) {
        alert('Geolocation not supported.');
        return;
      }

      btn.textContent = 'Stop Tracking';
      status.textContent = 'Tracking started...';

      watchId = navigator.geolocation.watchPosition(async pos => {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        const success = await sendLocation(lat, lng);
        status.textContent = success 
          ? `Location sent: ${lat.toFixed(5)}, ${lng.toFixed(5)}`
          : 'Failed to send location.';
      }, err => {
        console.error(err);
        status.textContent = 'GPS error: ' + err.message;
      }, {
        enableHighAccuracy: true,
        maximumAge: 5000,
        timeout: 10000
      });
    }
  </script>
</body>
</html>
