<?php
session_start();
require '../backend/db.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $conn->query("DELETE FROM cars WHERE id = $id");
    header("Location: cars.php");
    exit;
}

// Handle add
if (isset($_POST['add'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $price = floatval($_POST['price']);
    $status = $conn->real_escape_string($_POST['status']);
    $image = $conn->real_escape_string($_POST['image']);
    $conn->query("INSERT INTO cars (name, image, price, status) VALUES ('$name', '$image', $price, '$status')");
    header("Location: cars.php");
    exit;
}

// Handle update
if (isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $price = floatval($_POST['price']);
    $status = $conn->real_escape_string($_POST['status']);
    $image = $conn->real_escape_string($_POST['image']);
    $conn->query("UPDATE cars SET name='$name', price=$price, status='$status', image='$image' WHERE id=$id");
    header("Location: cars.php");
    exit;
}

// Handle JSON for map
if (isset($_GET['json'])) {
    $result = $conn->query("SELECT * FROM cars");
    $cars = [];
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($cars);
    exit;
}

$cars = $conn->query("SELECT * FROM cars");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Car Management</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            'rental-dark': '#0f172a',
            'rental-primary': '#FF4000',
            'rental-accent': '#f59e0b',
            'rental-gray': '#1e293b',
          },
          animation: {
            'fade-in': 'fadeIn 0.5s ease-in',
            'slide-up': 'slideUp 0.3s ease-out',
          },
          keyframes: {
            fadeIn: {
              '0%': { opacity: '0' },
              '100%': { opacity: '1' },
            },
            slideUp: {
              '0%': { transform: 'translateY(20px)', opacity: '0' },
              '100%': { transform: 'translateY(0)', opacity: '1' },
            }
          }
        }
      }
    }
  </script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    body {
      font-family: 'Inter', sans-serif;
    }
    
    .nav-item {
      transition: all 0.2s ease;
    }
    
    .nav-item:hover {
      background: rgba(255, 64, 0, 0.1);
    }
    
    .nav-item.active {
      background: rgba(255, 64, 0, 0.2);
      border-left: 3px solid #FF4000;
    }
    
    #map { 
      height: 400px;
      border-radius: 0.75rem;
      border: 1px solid #334155;
      z-index: 1;
    }
    
    .table-row:hover {
      background-color: rgba(255, 255, 255, 0.05);
    }
  </style>
</head>
<body class="bg-rental-dark text-gray-300 min-h-screen">

  <!-- Animated Background Elements -->
  <div class="fixed inset-0 overflow-hidden z-0">
    <div class="absolute bottom-0 left-0 w-full h-1/3 bg-gradient-to-t from-black to-transparent z-10"></div>
    <div class="absolute top-20 right-20 w-32 h-32 rounded-full bg-rental-primary opacity-10 blur-3xl"></div>
    <div class="absolute bottom-40 left-40 w-40 h-40 rounded-full bg-rental-accent opacity-10 blur-3xl"></div>
  </div>

  <!-- Sidebar -->
  <aside class="fixed z-40 w-64 h-screen bg-gray-900 bg-opacity-80 backdrop-blur-md border-r border-gray-800 p-6 space-y-6">
    <div class="flex items-center space-x-2 mb-8">
      <i class="fas fa-car text-rental-primary text-2xl"></i>
      <span class="text-xl font-bold text-white">Arnie<span class="text-rental-primary">Rentals</span></span>
    </div>
    
    <nav class="flex flex-col gap-1">
      <a href="../admin/admin_dashboard.php" class="nav-item px-4 py-3 rounded-lg text-gray-300 flex items-center space-x-3">
        <i class="fas fa-chart-line w-5 text-center"></i>
        <span>Dashboard</span>
      </a>
      <a href="../admin/pendingrequest.php" class="nav-item px-4 py-3 rounded-lg text-gray-300 hover:text-white flex items-center space-x-3">
        <i class="fas fa-clock w-5 text-center"></i>
        <span>Pending Requests</span>
        <?php 
          $pendingCount = $conn->query("SELECT COUNT(*) FROM reservations WHERE status = 'pending'")->fetch_row()[0];
          if ($pendingCount > 0): ?>
        <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-1 rounded-full"><?= $pendingCount ?></span>
        <?php endif; ?>
      </a>
      <a href="../admin/booking_history.php" class="nav-item px-4 py-3 rounded-lg text-gray-300 hover:text-white flex items-center space-x-3">
        <i class="fas fa-history w-5 text-center"></i>
        <span>Booking History</span>
      </a>
      <a href="../admin/cars.php" class="nav-item active px-4 py-3 rounded-lg text-white flex items-center space-x-3">
        <i class="fas fa-car w-5 text-center"></i>
        <span>Car Management</span>
      </a>
      <a href="../logout.php" class="nav-item px-4 py-3 rounded-lg text-red-400 hover:text-red-300 hover:bg-red-900 hover:bg-opacity-30 mt-4 flex items-center space-x-3">
        <i class="fas fa-sign-out-alt w-5 text-center"></i>
        <span>Logout</span>
      </a>
    </nav>
    
    <div class="absolute bottom-6 left-6 right-6 text-xs text-gray-500">
      <p>© 2023 ArnieRentals</p>
      <p>v1.0.0</p>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="ml-64 flex-1 p-8 relative z-10">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl font-bold text-white">Car Management</h1>
      <div class="flex items-center space-x-4">
        <div class="relative">
          <i class="fas fa-bell text-gray-400 hover:text-white cursor-pointer"></i>
          <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
        </div>
        <div class="flex items-center space-x-2">
          <div class="w-8 h-8 rounded-full bg-rental-primary flex items-center justify-center text-white">
            <i class="fas fa-user"></i>
          </div>
          <span class="text-sm">Admin</span>
        </div>
      </div>
    </div>

    <!-- Add Car Form -->
    <div class="bg-rental-gray bg-opacity-70 p-6 rounded-xl border border-gray-700 mb-8">
      <h2 class="text-lg font-semibold mb-4 text-white">Add New Car</h2>
      <form method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
          <label class="block text-gray-400 text-sm mb-1">Car Name</label>
          <input type="text" name="name" placeholder="Car Name" required 
                 class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-rental-primary focus:border-transparent">
        </div>
        <div>
          <label class="block text-gray-400 text-sm mb-1">Image Filename</label>
          <input type="text" name="image" placeholder="car-image.jpg" required 
                 class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-rental-primary focus:border-transparent">
        </div>
        <div>
          <label class="block text-gray-400 text-sm mb-1">Price Per Day</label>
          <input type="number" name="price" placeholder="1500.00" step="0.01" required 
                 class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-rental-primary focus:border-transparent">
        </div>
        <div>
          <label class="block text-gray-400 text-sm mb-1">Status</label>
          <select name="status" 
                  class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-rental-primary focus:border-transparent">
            <option value="available">Available</option>
            <option value="maintenance">Maintenance</option>
          </select>
        </div>
        <div class="flex items-end">
          <button type="submit" name="add" 
                  class="w-full bg-rental-primary hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-plus mr-2"></i> Add Car
          </button>
        </div>
      </form>
    </div>

    <!-- Car Table -->
    <div class="bg-rental-gray bg-opacity-70 rounded-xl border border-gray-700 overflow-hidden mb-8">
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead class="bg-gray-800">
            <tr>
              <th class="py-3 px-4 text-left text-gray-300 font-medium">Car</th>
              <th class="py-3 px-4 text-left text-gray-300 font-medium">Image</th>
              <th class="py-3 px-4 text-left text-gray-300 font-medium">Price</th>
              <th class="py-3 px-4 text-left text-gray-300 font-medium">Status</th>
              <th class="py-3 px-4 text-left text-gray-300 font-medium">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-700">
            <?php while($car = $cars->fetch_assoc()): ?>
            <tr class="table-row">
              <form method="POST">
                <input type="hidden" name="id" value="<?= $car['id'] ?>">
                <td class="py-3 px-4">
                  <input type="text" name="name" value="<?= htmlspecialchars($car['name']) ?>" 
                         class="w-full bg-gray-700 border border-gray-600 text-white px-2 py-1 rounded text-sm focus:ring-1 focus:ring-rental-primary">
                </td>
                <td class="py-3 px-4">
                  <input type="text" name="image" value="<?= htmlspecialchars($car['image']) ?>" 
                         class="w-full bg-gray-700 border border-gray-600 text-white px-2 py-1 rounded text-sm focus:ring-1 focus:ring-rental-primary">
                </td>
                <td class="py-3 px-4">
                  <input type="number" step="0.01" name="price" value="<?= $car['price'] ?>" 
                         class="w-full bg-gray-700 border border-gray-600 text-white px-2 py-1 rounded text-sm focus:ring-1 focus:ring-rental-primary">
                </td>
                <td class="py-3 px-4">
                  <select name="status" 
                          class="w-full bg-gray-700 border border-gray-600 text-white px-2 py-1 rounded text-sm focus:ring-1 focus:ring-rental-primary">
                    <option value="available" <?= $car['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                    <option value="maintenance" <?= $car['status'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                  </select>
                </td>
                <td class="py-3 px-4 flex gap-2">
                  <button type="submit" name="edit" 
                          class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition">
                    <i class="fas fa-save mr-1"></i> Update
                  </button>
                  <a href="?delete=<?= $car['id'] ?>" 
                     class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition"
                     onclick="return confirm('Are you sure you want to delete this car?')">
                    <i class="fas fa-trash mr-1"></i> Delete
                  </a>
                  <a href="live_track_cp.php?car_id=<?= $car['id'] ?>" 
                     class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm transition">
                    <i class="fas fa-map-marker-alt mr-1"></i> Track
                  </a>
                </td>
              </form>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Map -->
    <div class="bg-rental-gray bg-opacity-70 rounded-xl border border-gray-700 p-4 mb-8">
      <h2 class="text-lg font-semibold mb-4 text-white">Vehicle Locations</h2>
      <div id="map"></div>
    </div>

    <script>
      let conn = new WebSocket("ws://n4cc8sswss0ckw88kc0kowww.18.142.5.155.sslip.io?username=admin&password=AdminSecure123")

      conn.onopen = function() {
        console.log("Open")
        conn.send("Hello from Server")
      }

      conn.onmessage = function(e) {
          console.log(`Message from client: ${JSON.stringify(e.data)}`)
      }
      const map = L.map('map').setView([10.3157, 123.8854], 12);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(map);

      let markers = {};

      function loadMarkers() {
        fetch('cars.php?json=1')
          .then(res => res.json())
          .then(data => {
            // Clear existing markers
            for (const id in markers) {
              map.removeLayer(markers[id]);
            }
            markers = {};

            let bounds = [];
            data.forEach(car => {
              if (car.latitude !== null && car.longitude !== null) {
                const lat = parseFloat(car.latitude);
                const lng = parseFloat(car.longitude);
                bounds.push([lat, lng]);

                const color = car.status === 'available' ? '#4ade80' : '#f87171';
                const fillColor = car.status === 'available' ? '#166534' : '#991b1b';

                const marker = L.circleMarker([lat, lng], {
                  radius: 10,
                  color,
                  fillColor,
                  fillOpacity: 0.9,
                  weight: 2
                }).addTo(map);

                marker.bindPopup(`
                  <div class="text-gray-800">
                    <h3 class="font-bold text-lg">${car.name}</h3>
                    <p><span class="font-semibold">Status:</span> 
                      <span class="${car.status === 'available' ? 'text-green-600' : 'text-red-600'}">
                        ${car.status.charAt(0).toUpperCase() + car.status.slice(1)}
                      </span>
                    </p>
                    <p><span class="font-semibold">Price:</span> ₱${car.price.toFixed(2)}/day</p>
                    <a href="live_track_cp.php?car_id=${car.id}" 
                       class="inline-block mt-2 px-2 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                      <i class="fas fa-map-marker-alt mr-1"></i> Live Track
                    </a>
                  </div>
                `);
                markers[car.id] = marker;
              }
            });

            if (bounds.length > 0) {
              map.fitBounds(bounds, { padding: [50, 50] });
            }
          });
      }

      // Initial load
      loadMarkers();
      setInterval(loadMarkers, 5000);
    </script>
  </main>
</body>
</html>