<?php
require '../backend/db.php';
$car_id = intval($_GET['car_id']);
$car = $conn->query("SELECT * FROM cars WHERE id=$car_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <title>Live Track - <?= htmlspecialchars($car['name']) ?></title>
</head>
<body>
  <h2>Tracking <?= htmlspecialchars($car['name']) ?></h2>
  <div id="map" style="height: 90vh;"></div>
  <script>
    const carId = <?= $car_id ?>;
    let clientWs = new WebSocket("ws://n4cc8sswss0ckw88kc0kowww.18.142.5.155.sslip.io?username=client&password=ClientSecure456");
    
    clientWs.onopen = function() {
      navigator.geolocation.watchPosition(
      function(position) {
        console.log(position, "position")
        clientWs.send(JSON.stringify({
          latitude: position.coords.latitude,
          longitude: position.coords.longitude,
          carId: carId,
        }))
      },
      function(error) {
        console.error(`Geolocation err: ${error}`)
      },
      {
        enableHighAccuracy: false,
        maximumAge: 0,
        timeout: 5000,
      }
    )

    }

    clientWs.onmessage = function(e) {
        console.log(`Message from client: ${JSON.stringify(e.data)}`)
    }

    const map = L.map('map').setView([10.3157, 123.8854], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    let marker;

    function loadLocation() {
      fetch('../cars.php?json=1')
        .then(res => res.json())
        .then(data => {
          const car = data.find(c => c.id == carId);
          if (car && car.latitude && car.longitude) {
            const latlng = [parseFloat(car.latitude), parseFloat(car.longitude)];
            if (marker) {
              marker.setLatLng(latlng);
            } else {
              marker = L.marker(latlng).addTo(map);
            }
            map.setView(latlng);
          }
        });
    }

    // loadLocation();
    // setInterval(loadLocation, 5000);
  </script>
</body>
</html>
