<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

$user = $_SESSION['user'];
require '../backend/db.php';

$cars = $conn->query("SELECT * FROM cars");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>DriveEasy - Premium Car Rentals</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            'rental-dark': '#0f172a',
            'rental-primary': '#FF4000',
            'rental-accent': '#f59e0b',
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
    
    .car-card {
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .car-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px -5px rgba(255, 64, 0, 0.4);
    }
    
    .floating-car {
      animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-15px); }
      100% { transform: translateY(0px); }
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

    <!-- Navigation -->
    <nav class="relative z-50 bg-gray-900 bg-opacity-80 backdrop-blur-md border-b border-gray-800">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="../assets/images/Logo.png" class="w-12" alt="">
                <span class="text-lg font-bold text-rental-primary">Arnie<span class="text-white">Rent a Car</span></span>
            </div>
            
            <div class="flex items-center space-x-4">
              <a href="profile.php" class="text-gray-300 hover:text-rental-primary transition">
                <i class="fas fa-calendar-alt mr-1"></i> My Reservations
              </a>
              <a href="../auth/logout.php" class="text-gray-300 hover:text-rental-primary transition">
                <i class="fas fa-sign-out-alt mr-1"></i> Logout
              </a>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="relative z-10 container mx-auto px-4 py-8">
        <div class="bg-gray-800 bg-opacity-70 rounded-xl p-6 border border-gray-700">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-2xl font-bold text-white">Welcome back, <span class="text-rental-primary"><?= htmlspecialchars($user['firstname']) ?></span>!</h1>
                    <p class="text-gray-400 text-sm"><?= htmlspecialchars($user['email']) ?></p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-rental-primary bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-rental-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Car Grid -->
    <section class="relative z-10 container mx-auto px-4 py-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-white">Available <span class="text-rental-primary">Vehicles</span></h2>
            <p class="text-gray-400 text-sm">Choose from our premium collection</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($car = $cars->fetch_assoc()): ?>
            <div class="car-card bg-gray-800 rounded-xl overflow-hidden border border-gray-700 relative <?= $car['status'] === 'maintenance' ? 'opacity-70' : '' ?>">
                <?php if ($car['status'] === 'maintenance'): ?>
                    <div class="absolute top-0 left-0 w-full bg-red-600 text-white text-xs text-center py-1 rounded-t">
                        Under Maintenance
                    </div>
                <?php endif; ?>
                
                <div class="h-48 bg-gray-700 flex items-center justify-center p-4">
                    <img src="../images/<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['name']) ?>" class="h-full object-contain">
                </div>
                
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-bold text-white"><?= htmlspecialchars($car['name']) ?></h3>
                        <div class="text-rental-accent font-bold">₱<?= number_format($car['price'], 2) ?><span class="text-gray-400 text-sm">/day</span></div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-2 text-center text-xs mb-3">
                        <div class="bg-gray-700 py-2 rounded">
                            <i class="fas fa-user-friends text-gray-400"></i>
                            <p class="mt-1">5 Seats</p>
                        </div>
                        <div class="bg-gray-700 py-2 rounded">
                            <i class="fas fa-suitcase text-gray-400"></i>
                            <p class="mt-1">3 Bags</p>
                        </div>
                        <div class="bg-gray-700 py-2 rounded">
                            <i class="fas fa-tachometer-alt text-gray-400"></i>
                            <p class="mt-1">Auto</p>
                        </div>
                    </div>
                    
                    <button 
                        onclick="openReservationModal('<?= htmlspecialchars(addslashes($car['name'])) ?>', <?= $car['price'] ?>, <?= $car['id'] ?>)" 
                        class="w-full bg-rental-primary hover:bg-orange-700 text-white py-2 rounded-lg transition text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        <?= $car['status'] === 'maintenance' ? 'disabled' : '' ?>>
                        <i class="fas fa-calendar-check mr-2"></i> Reserve Now
                    </button>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Reservation Modal -->
    <div id="reservationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden w-full max-w-md animate-slide-up" 
             onclick="event.stopPropagation()">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-white">Car Reservation</h2>
                    <button onclick="closeReservationModal()" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="../reservation/save_reservation.php" method="POST" class="space-y-4" id="reservationForm">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <input type="hidden" id="carIdInput" name="car_id">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-300 text-sm mb-1">First Name</label>
                            <input type="text" value="<?= htmlspecialchars($user['firstname']) ?>" 
                                   class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm" readonly>
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm mb-1">Last Name</label>
                            <input type="text" value="<?= htmlspecialchars($user['lastname']) ?>" 
                                   class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm" readonly>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-300 text-sm mb-1">Email</label>
                        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" 
                               class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm" readonly>
                    </div>

                    <div>
                        <label class="block text-gray-300 text-sm mb-1">Contact Number</label>
                        <input type="text" name="contact_number" required 
                               class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-rental-primary focus:border-transparent"
                               placeholder="Enter your contact number">
                    </div>

                    <div>
                        <label class="block text-gray-300 text-sm mb-1">Drive Option</label>
                        <select name="drive_option" id="driveOption" required 
                                class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-rental-primary focus:border-transparent">
                            <option value="">-- Select Option --</option>
                            <option value="self">Self Drive</option>
                            <option value="hire">Hire a Driver</option>
                        </select>
                    </div>

                    <div id="licenseField" class="hidden">
                        <label class="block text-gray-300 text-sm mb-1">Driver's License ID</label>
                        <input type="text" name="driver_license_id" 
                               class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-rental-primary focus:border-transparent"
                               placeholder="Enter your driver's license ID">
                    </div>

                    <div id="govIdField" class="hidden">
                        <label class="block text-gray-300 text-sm mb-1">Government Valid ID</label>
                        <input type="text" name="gov_id" 
                               class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-rental-primary focus:border-transparent"
                               placeholder="Enter any government-issued valid ID">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-300 text-sm mb-1">Pickup Date</label>
                            <input type="text" name="pickup_date" id="pickup_date" required 
                                   class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-rental-primary focus:border-transparent"
                                   placeholder="Select date">
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm mb-1">Return Date</label>
                            <input type="text" name="return_date" id="return_date" required 
                                   class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-rental-primary focus:border-transparent"
                                   placeholder="Select date">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-300 text-sm mb-1">Car Name</label>
                        <input type="text" id="carNameInput" readonly 
                               class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-300 text-sm mb-1">Price Per Day</label>
                            <input type="text" id="priceInput" readonly 
                                   class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-gray-300 text-sm mb-1">Total Price</label>
                            <input type="text" id="totalPriceInput" readonly 
                                   class="w-full bg-gray-700 border border-gray-600 text-white px-3 py-2 rounded-lg text-sm font-bold text-rental-primary">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="closeReservationModal()" 
                                class="px-4 py-2 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 transition">
                            Cancel
                        </button>
                        <button type="submit" name="submit" 
                                class="px-4 py-2 bg-rental-primary text-white rounded-lg hover:bg-orange-700 transition">
                            Confirm Reservation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    let pickupPicker, returnPicker;

    function openReservationModal(carName, price, carId) {
        document.getElementById('carNameInput').value = carName;
        document.getElementById('priceInput').value = price;
        document.getElementById('carIdInput').value = carId;
        document.getElementById('pickup_date').value = '';
        document.getElementById('return_date').value = '';
        document.getElementById('totalPriceInput').value = '';
        document.getElementById('licenseField').classList.add('hidden');
        document.getElementById('govIdField').classList.add('hidden');
        document.getElementById('reservationModal').classList.remove('hidden');

        // Fetch reserved dates for this car
        fetch(`get_reserved_dates.php?car_id=${carId}`)
            .then(res => res.json())
            .then(disabledDates => {
                pickupPicker = flatpickr("#pickup_date", {
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    disable: disabledDates,
                    onChange: calculateTotalPrice,
                });

                returnPicker = flatpickr("#return_date", {
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    disable: disabledDates,
                    onChange: calculateTotalPrice,
                });
            });
    }

    function closeReservationModal() {
        document.getElementById('reservationModal').classList.add('hidden');
    }

    function calculateTotalPrice() {
        const pricePerDay = parseFloat(document.getElementById('priceInput').value);
        const pickupDate = document.getElementById('pickup_date').value;
        const returnDate = document.getElementById('return_date').value;
        const totalPriceInput = document.getElementById('totalPriceInput');

        if (pickupDate && returnDate && pricePerDay) {
            const start = new Date(pickupDate);
            const end = new Date(returnDate);
            const diffTime = end - start;
            const days = diffTime > 0 ? Math.ceil(diffTime / (1000 * 60 * 60 * 24)) : 1;
            const total = pricePerDay * days;
            totalPriceInput.value = '₱' + total.toFixed(2);
        } else {
            totalPriceInput.value = '';
        }
    }

    document.getElementById('driveOption').addEventListener('change', function () {
        const option = this.value;
        const licenseField = document.getElementById('licenseField');
        const govIdField = document.getElementById('govIdField');

        if (option === 'self') {
            licenseField.classList.remove('hidden');
            govIdField.classList.add('hidden');
        } else if (option === 'hire') {
            licenseField.classList.add('hidden');
            govIdField.classList.remove('hidden');
        } else {
            licenseField.classList.add('hidden');
            govIdField.classList.add('hidden');
        }
    });

    // Close modal when clicking outside
    document.getElementById('reservationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReservationModal();
        }
    });
    </script>
</body>
</html>