<?php
session_start();
require '../backend/db.php';

// Get all pending reservations
$result = $conn->query("SELECT * FROM reservations WHERE status = 'pending' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pending Reservations</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
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
    
    #toast {
      visibility: hidden;
      min-width: 250px;
      background-color: #4caf50;
      color: white;
      text-align: center;
      border-radius: 4px;
      padding: 16px;
      position: fixed;
      z-index: 9999;
      right: 20px;
      bottom: 30px;
      font-size: 17px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.3);
      transition: visibility 0s, opacity 0.5s ease-in-out;
      opacity: 0;
    }
    
    #toast.show {
      visibility: visible;
      opacity: 1;
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
      <a href="../admin/pendingrequest.php" class="nav-item active px-4 py-3 rounded-lg text-white flex items-center space-x-3">
        <i class="fas fa-clock w-5 text-center"></i>
        <span>Pending Requests</span>
        <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-1 rounded-full"><?= $result->num_rows ?></span>
      </a>
      <a href="../admin/booking_history.php" class="nav-item px-4 py-3 rounded-lg text-gray-300 hover:text-white flex items-center space-x-3">
        <i class="fas fa-history w-5 text-center"></i>
        <span>Booking History</span>
      </a>
      <a href="../admin/cars.php" class="nav-item px-4 py-3 rounded-lg text-gray-300 hover:text-white flex items-center space-x-3">
        <i class="fas fa-history w-5 text-center"></i>
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
      <h1 class="text-2xl font-bold text-white">Pending Reservation Requests</h1>
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

    <div id="toast"></div>

    <?php if ($result->num_rows > 0): ?>
      <div class="bg-rental-gray bg-opacity-70 rounded-xl border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead class="bg-gray-800">
              <tr>
                <th class="py-3 px-4 text-left text-gray-300 font-medium">Full Name</th>
                <th class="py-3 px-4 text-left text-gray-300 font-medium">Car</th>
                <th class="py-3 px-4 text-left text-gray-300 font-medium">Pickup</th>
                <th class="py-3 px-4 text-left text-gray-300 font-medium">Return</th>
                <th class="py-3 px-4 text-left text-gray-300 font-medium">Total Price</th>
                <th class="py-3 px-4 text-left text-gray-300 font-medium">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="table-row">
                  <td class="py-3 px-4"><?= htmlspecialchars($row['full_name']) ?></td>
                  <td class="py-3 px-4"><?= htmlspecialchars($row['car_name']) ?></td>
                  <td class="py-3 px-4"><?= htmlspecialchars($row['pickup_date']) ?></td>
                  <td class="py-3 px-4"><?= htmlspecialchars($row['return_date']) ?></td>
                  <td class="py-3 px-4">₱<?= number_format($row['total_price'], 2) ?></td>
                  <td class="py-3 px-4 flex items-center gap-2">
                    <button 
                      onclick='openViewModal(<?= json_encode($row, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'
                      class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition"
                    >
                      <i class="fas fa-eye mr-1"></i> View
                    </button>
                    <a href="approve.php?id=<?= $row['id'] ?>" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm transition">
                      <i class="fas fa-check mr-1"></i> Approve
                    </a>
                    <a href="decline.php?id=<?= $row['id'] ?>" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition">
                      <i class="fas fa-times mr-1"></i> Decline
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php else: ?>
      <div class="bg-rental-gray bg-opacity-70 p-8 rounded-xl border border-gray-700 text-center">
        <i class="fas fa-clock text-4xl text-gray-500 mb-4"></i>
        <h3 class="text-xl font-medium text-gray-300 mb-2">No Pending Requests</h3>
        <p class="text-gray-500">All reservation requests have been processed.</p>
      </div>
    <?php endif; ?>

    <!-- View Modal -->
    <div id="viewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-rental-gray border border-gray-700 rounded-xl overflow-hidden w-full max-w-md animate-slide-up" 
           onclick="event.stopPropagation()">
        <div class="p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-white">Reservation Details</h2>
            <button onclick="closeViewModal()" class="text-gray-400 hover:text-white">
              <i class="fas fa-times"></i>
            </button>
          </div>
          <div id="modalContent" class="space-y-3 text-gray-300 text-sm">
            <!-- Content populated by JS -->
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    function formatDate(dateStr) {
      if (!dateStr || dateStr === "0000-00-00" || dateStr === "0000-00-00 00:00:00") return 'N/A';

      const isoDateStr = dateStr.includes(' ') ? dateStr.replace(' ', 'T') : dateStr;

      const date = new Date(isoDateStr);

      if (isNaN(date)) return 'Invalid Date';

      return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    }

    function openViewModal(data) {
      const modal = document.getElementById('viewModal');
      const content = document.getElementById('modalContent');

      content.innerHTML = `
        <div class="grid grid-cols-2 gap-4">
          <div>
            <p class="text-gray-400">Full Name:</p>
            <p class="text-white">${data.full_name}</p>
          </div>
          <div>
            <p class="text-gray-400">Email:</p>
            <p class="text-white">${data.email || 'N/A'}</p>
          </div>
          <div>
            <p class="text-gray-400">Contact Number:</p>
            <p class="text-white">${data.contact_number}</p>
          </div>
          <div>
            <p class="text-gray-400">Driver's License:</p>
            <p class="text-white">${data.driver_license_id || 'N/A'}</p>
          </div>
          <div>
            <p class="text-gray-400">Car Name:</p>
            <p class="text-white">${data.car_name}</p>
          </div>
          <div>
            <p class="text-gray-400">Price Per Day:</p>
            <p class="text-white">₱${parseFloat(data.price_per_day || 0).toFixed(2)}</p>
          </div>
          <div>
            <p class="text-gray-400">Pickup Date:</p>
            <p class="text-white">${formatDate(data.pickup_date)}</p>
          </div>
          <div>
            <p class="text-gray-400">Return Date:</p>
            <p class="text-white">${formatDate(data.return_date)}</p>
          </div>
          <div>
            <p class="text-gray-400">Total Price:</p>
            <p class="text-white font-bold text-rental-primary">₱${parseFloat(data.total_price || 0).toFixed(2)}</p>
          </div>
          <div>
            <p class="text-gray-400">Status:</p>
            <p class="text-white">
              <span class="px-2 py-1 rounded text-xs ${data.status === 'pending' ? 'bg-yellow-500' : data.status === 'approved' ? 'bg-green-500' : 'bg-red-500'}">
                ${data.status}
              </span>
            </p>
          </div>
          <div class="col-span-2">
            <p class="text-gray-400">Requested At:</p>
            <p class="text-white">${formatDate(data.created_at)}</p>
          </div>
        </div>
      `;

      modal.classList.remove('hidden');
    }

    function closeViewModal() {
      document.getElementById('viewModal').classList.add('hidden');
    }
  </script>
</body>
</html>