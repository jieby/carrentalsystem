<?php
session_start();
require '../backend/db.php';

// Get booking history excluding pending
$query = "SELECT * FROM reservations WHERE status != 'pending' ORDER BY created_at DESC";
$result = $conn->query($query);

// Status color mapping
$statusColors = [
    'approved'  => 'bg-green-900 text-green-300',
    'rejected'  => 'bg-red-900 text-red-300',
    'completed' => 'bg-blue-900 text-blue-300',
    'cancelled' => 'bg-gray-700 text-gray-300',
];
?>
<!DOCTYPE html>
<html lang="tl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
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
            <a href="../admin/booking_history.php" class="nav-item active px-4 py-3 rounded-lg text-white flex items-center space-x-3">
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
            <h1 class="text-2xl font-bold text-white">Booking History</h1>
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

        <?php if ($result->num_rows > 0): ?>
            <!-- Search and Filter -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-500"></i>
                    </div>
                    <input 
                        type="text" 
                        id="searchInput" 
                        placeholder="Search bookings..."
                        class="pl-10 pr-4 py-2 bg-rental-gray border border-gray-700 rounded-lg focus:ring-2 focus:ring-rental-primary focus:outline-none w-full text-white placeholder-gray-500"
                    >
                </div>

                <div class="flex-1 md:flex-none md:w-48">
                    <select 
                        id="statusFilter"
                        class="px-4 py-2 bg-rental-gray border border-gray-700 rounded-lg focus:ring-2 focus:ring-rental-primary focus:outline-none w-full text-white"
                    >
                        <option value="">All Status</option>
                        <option value="approved">Approved</option>
                        <option value="declined">Declined</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <!-- Booking Table -->
            <div class="bg-rental-gray bg-opacity-70 rounded-xl border border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full" id="bookingTable">
                        <thead class="bg-gray-800">
                            <tr>
                                <th class="py-3 px-4 text-left text-gray-300 font-medium">Name</th>
                                <th class="py-3 px-4 text-left text-gray-300 font-medium">Car</th>
                                <th class="py-3 px-4 text-left text-gray-300 font-medium">Pickup</th>
                                <th class="py-3 px-4 text-left text-gray-300 font-medium">Return</th>
                                <th class="py-3 px-4 text-left text-gray-300 font-medium">Full Payment</th>
                                <th class="py-3 px-4 text-left text-gray-300 font-medium">Status</th>
                                <th class="py-3 px-4 text-left text-gray-300 font-medium">Date of Booking</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <?php
                                    $statusClass = $statusColors[$row['status']] ?? 'bg-yellow-900 text-yellow-300';
                                ?>
                                <tr class="table-row">
                                    <td class="py-3 px-4"><?= htmlspecialchars($row['full_name']) ?></td>
                                    <td class="py-3 px-4"><?= htmlspecialchars($row['car_name']) ?></td>
                                    <td class="py-3 px-4"><?= date("M d, Y h:i A", strtotime($row['pickup_date'])) ?></td>
                                    <td class="py-3 px-4"><?= date("M d, Y h:i A", strtotime($row['return_date'])) ?></td>
                                    <td class="py-3 px-4 font-semibold text-rental-primary">₱<?= number_format($row['total_price'], 2) ?></td>
                                    <td class="py-3 px-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $statusClass ?>">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4"><?= date("M d, Y h:i A", strtotime($row['created_at'])) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Filtering Script -->
            <script>
                const searchInput = document.getElementById("searchInput");
                const statusFilter = document.getElementById("statusFilter");
                const tableRows = document.querySelectorAll("#bookingTable tbody tr");

                function filterTable() {
                    const searchText = searchInput.value.toLowerCase();
                    const statusValue = statusFilter.value.toLowerCase();

                    tableRows.forEach(row => {
                        const cells = row.querySelectorAll("td");

                        // Only include Name, Car, Pickup, Return, Payment, Date (exclude Status)
                        const searchColumns = [0, 1, 2, 3, 4, 6]
                            .map(i => cells[i].textContent.toLowerCase())
                            .join(" ");

                        const statusText = cells[5].textContent.toLowerCase();

                        const matchesSearch = searchColumns.includes(searchText);
                        const matchesStatus = !statusValue || statusText.includes(statusValue);

                        row.style.display = matchesSearch && matchesStatus ? "" : "none";
                    });
                }

                searchInput.addEventListener("input", filterTable);
                statusFilter.addEventListener("change", filterTable);
            </script>

        <?php else: ?>
            <div class="bg-rental-gray bg-opacity-70 p-8 rounded-xl border border-gray-700 text-center">
                <i class="fas fa-history text-4xl text-gray-500 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-300 mb-2">No Booking History</h3>
                <p class="text-gray-500">No completed bookings found in the system.</p>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>