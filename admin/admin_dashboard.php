<?php
session_start();
require '../backend/db.php';

// Income summaries
$weekly = $conn->query("SELECT SUM(total_price) AS total FROM reservations WHERE status = 'approved' AND created_at >= NOW() - INTERVAL 7 DAY")->fetch_assoc()['total'] ?? 0;
$monthly = $conn->query("SELECT SUM(total_price) AS total FROM reservations WHERE status = 'approved' AND MONTH(created_at) = MONTH(CURDATE())")->fetch_assoc()['total'] ?? 0;
$yearly = $conn->query("SELECT SUM(total_price) AS total FROM reservations WHERE status = 'approved' AND YEAR(created_at) = YEAR(CURDATE())")->fetch_assoc()['total'] ?? 0;

// Stats
$totalCars = $conn->query("SELECT COUNT(*) AS count FROM cars")->fetch_assoc()['count'];
$totalUsers = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$totalReservations = $conn->query("SELECT COUNT(*) AS count FROM reservations")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
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
    
    .stat-card {
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px -5px rgba(255, 64, 0, 0.2);
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
      <a href="../admin/admin_dashboard.php" class="nav-item active px-4 py-3 rounded-lg text-gray-300 flex items-center space-x-3">
        <i class="fas fa-chart-line w-5 text-center"></i>
        <span>Dashboard</span>
      </a>
      <a href="pendingrequest.php" class="nav-item px-4 py-3 rounded-lg text-gray-300 hover:text-white flex items-center space-x-3">
        <i class="fas fa-clock w-5 text-center"></i>
        <span>Pending Requests</span>
        <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">3</span>
      </a>
      <a href="booking_history.php" class="nav-item px-4 py-3 rounded-lg text-gray-300 hover:text-white flex items-center space-x-3">
        <i class="fas fa-history w-5 text-center"></i>
        <span>Booking History</span>
      </a>
      <a href="cars.php" class="nav-item px-4 py-3 rounded-lg text-gray-300 hover:text-white flex items-center space-x-3">
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
      <h1 class="text-3xl font-bold text-white">Dashboard Overview</h1>
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

    <!-- Income Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="stat-card bg-rental-gray bg-opacity-70 p-6 rounded-xl border border-gray-700 hover:border-rental-primary">
        <div class="flex justify-between items-start">
          <div>
            <p class="text-gray-400 text-sm">Weekly Income</p>
            <p class="text-2xl font-bold text-green-400 mt-1">₱<?= number_format($weekly, 2) ?></p>
          </div>
          <div class="w-10 h-10 bg-green-900 bg-opacity-30 rounded-full flex items-center justify-center text-green-400">
            <i class="fas fa-calendar-week"></i>
          </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-700 flex items-center">
          <span class="text-green-400 text-xs"><i class="fas fa-arrow-up mr-1"></i> 12% from last week</span>
        </div>
      </div>
      
      <div class="stat-card bg-rental-gray bg-opacity-70 p-6 rounded-xl border border-gray-700 hover:border-rental-primary">
        <div class="flex justify-between items-start">
          <div>
            <p class="text-gray-400 text-sm">Monthly Income</p>
            <p class="text-2xl font-bold text-blue-400 mt-1">₱<?= number_format($monthly, 2) ?></p>
          </div>
          <div class="w-10 h-10 bg-blue-900 bg-opacity-30 rounded-full flex items-center justify-center text-blue-400">
            <i class="fas fa-calendar-alt"></i>
          </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-700 flex items-center">
          <span class="text-blue-400 text-xs"><i class="fas fa-arrow-up mr-1"></i> 8% from last month</span>
        </div>
      </div>
      
      <div class="stat-card bg-rental-gray bg-opacity-70 p-6 rounded-xl border border-gray-700 hover:border-rental-primary">
        <div class="flex justify-between items-start">
          <div>
            <p class="text-gray-400 text-sm">Yearly Income</p>
            <p class="text-2xl font-bold text-purple-400 mt-1">₱<?= number_format($yearly, 2) ?></p>
          </div>
          <div class="w-10 h-10 bg-purple-900 bg-opacity-30 rounded-full flex items-center justify-center text-purple-400">
            <i class="fas fa-calendar"></i>
          </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-700 flex items-center">
          <span class="text-purple-400 text-xs"><i class="fas fa-arrow-up mr-1"></i> 22% from last year</span>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="stat-card bg-rental-gray bg-opacity-70 p-6 rounded-xl border border-gray-700 hover:border-rental-primary">
        <div class="flex justify-between items-start">
          <div>
            <p class="text-gray-400 text-sm">Total Cars</p>
            <p class="text-2xl font-bold text-white mt-1"><?= $totalCars ?></p>
          </div>
          <div class="w-10 h-10 bg-rental-primary bg-opacity-20 rounded-full flex items-center justify-center text-rental-primary">
            <i class="fas fa-car"></i>
          </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-700">
          <a href="#" class="text-rental-primary text-xs hover:underline">View all cars <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
      </div>
      
      <div class="stat-card bg-rental-gray bg-opacity-70 p-6 rounded-xl border border-gray-700 hover:border-rental-primary">
        <div class="flex justify-between items-start">
          <div>
            <p class="text-gray-400 text-sm">Total Users</p>
            <p class="text-2xl font-bold text-white mt-1"><?= $totalUsers ?></p>
          </div>
          <div class="w-10 h-10 bg-rental-primary bg-opacity-20 rounded-full flex items-center justify-center text-rental-primary">
            <i class="fas fa-users"></i>
          </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-700">
          <a href="#" class="text-rental-primary text-xs hover:underline">Manage users <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
      </div>
      
      <div class="stat-card bg-rental-gray bg-opacity-70 p-6 rounded-xl border border-gray-700 hover:border-rental-primary">
        <div class="flex justify-between items-start">
          <div>
            <p class="text-gray-400 text-sm">Total Reservations</p>
            <p class="text-2xl font-bold text-white mt-1"><?= $totalReservations ?></p>
          </div>
          <div class="w-10 h-10 bg-rental-primary bg-opacity-20 rounded-full flex items-center justify-center text-rental-primary">
            <i class="fas fa-calendar-check"></i>
          </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-700">
          <a href="#" class="text-rental-primary text-xs hover:underline">View reservations <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
      </div>
    </div>
  </main>

</body>
</html>