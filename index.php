<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>DriveEasy - Premium Car Rentals</title>
  <link rel="stylesheet" href="output.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        'pulse-slow': 'pulse 3s infinite',
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
    
    function toggleModal(id) {
      const modal = document.getElementById(id);
      modal.classList.toggle('hidden');
    }

    function closeOnOutsideClick(event, id) {
      if (event.target.id === id) {
        toggleModal(id);
      }
    }

    function toggleMenu() {
      const menu = document.getElementById('mobileMenu');
      menu.classList.toggle('hidden');
    }
  </script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    body {
      font-family: 'Inter', sans-serif;
    }
    
    .hero {
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1502877338535-766e1452684a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
    
    .car-card {
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .car-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    
    .car-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
    }
    
    .search-box {
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
    }
    
    .floating-car {
      animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-15px); }
      100% { transform: translateY(0px); }
    }
    
    /* Mobile-specific styles */
    @media (max-width: 640px) {
      .hero {
        background-attachment: scroll;
        padding-top: 100px;
        padding-bottom: 60px;
      }
      
      .hero h1 {
        font-size: 2rem;
      }
      
      .search-box {
        margin-top: -40px;
        margin-bottom: 40px;
      }
      
      .modal-content {
        width: 100%;
        border-radius: 1rem 1rem 0 0;
      }
      
      .car-card {
        margin-bottom: 20px;
      }
      
      .floating-car {
        max-width: 300px;
        margin-top: 20px;
      }
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
            
            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
              <button onclick="toggleMenu()" class="text-gray-300">
                <i class="fas fa-bars text-xl"></i>
              </button>
            </div>
            
            <!-- Desktop menu -->
            <div class="hidden md:flex space-x-6">
                <a href="#" class="hover:text-rental-primary transition">Home</a>
                <a href="#" class="hover:text-rental-primary transition">Vehicles</a>
                <a href="#" class="hover:text-rental-primary transition">Locations</a>
                <a href="#" class="hover:text-rental-primary transition">About</a>
            </div>
            
            <div class="hidden md:flex items-center space-x-4">
              <button onclick="toggleModal('loginModal')" 
                      class="px-4 py-2 text-primary hover:bg-blue-50 rounded-lg transition font-medium">
                Login
              </button>
              <button onclick="toggleModal('registerModal')" 
                      class="px-4 py-2 bg-rental-primary text-white rounded-lg font-medium">
                Register
              </button>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobileMenu" class="hidden md:hidden bg-gray-800">
          <div class="container mx-auto px-4 py-3 flex flex-col space-y-3">
            <a href="#" class="block py-2 hover:text-rental-primary">Home</a>
            <a href="#" class="block py-2 hover:text-rental-primary">Vehicles</a>
            <a href="#" class="block py-2 hover:text-rental-primary">Locations</a>
            <a href="#" class="block py-2 hover:text-rental-primary">About</a>
            <div class="pt-2 border-t border-gray-700 flex space-x-3">
              <button onclick="toggleModal('loginModal')" 
                      class="w-1/2 py-2 text-primary border border-primary rounded-lg font-medium">
                Login
              </button>
              <button onclick="toggleModal('registerModal')" 
                      class="w-1/2 py-2 bg-rental-primary text-white rounded-lg font-medium">
                Register
              </button>
            </div>
          </div>
        </div>
    </nav>

 <!-- Hero Section -->
    <section class="relative z-10 pt-12 pb-20 px-4">
        <div class="container mx-auto flex flex-col items-center text-center">
            <div class="mb-8 animate-fade-in">
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">
                    Rent Your <span class="text-rental-primary">Dream Car</span> Today
                </h1>
                <p class="text-base mb-6 max-w-lg">
                    Choose from our premium collection of vehicles and enjoy the best rental experience.
                </p>
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 justify-center">
                    <button onclick="toggleModal('loginModal')" class="bg-rental-primary hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition animate-slide-up">
                        Browse Cars
                    </button>
                    <button class="border border-rental-primary text-rental-primary hover:bg-blue-900 hover:bg-opacity-20 px-6 py-3 rounded-lg transition animate-slide-up">
                        How It Works
                    </button>
                </div>
            </div>
            <div class="animate-fade-in">
                <img src="assets/images/mpv.png" 
                     alt="Luxury Car" 
                     class="floating-car max-w-xs w-full">
            </div>
        </div>
    </section>



     <!-- Popular Cars Section -->
    <section class="relative z-10 py-8 px-4">
        <div class="container mx-auto">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-white mb-2">Popular <span class="text-rental-primary">Vehicles</span></h2>
                <p class="max-w-2xl mx-auto text-sm">Explore our most sought-after rental cars</p>
            </div>

            <div class="space-y-6">
                <!-- Car Card 1 -->
                <div class="car-card bg-gray-800 rounded-xl overflow-hidden border border-gray-700 transition-all duration-300 hover:border-rental-primary">
                    <div class="relative h-48 bg-gray-700 flex items-center justify-center p-4">
                        <img src="assets/images/mpv.png" 
                             alt="Tesla Model S" 
                             class="h-full object-contain">
                        <div class="absolute top-4 right-4 bg-rental-primary text-white text-xs font-bold px-2 py-1 rounded">
                            Popular
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-white">MPV</h3>
                            <div class="text-rental-accent font-bold">P1500<span class="text-gray-400 text-sm">/day</span></div>
                        </div>
                        <div class="flex items-center text-gray-400 text-xs mb-3">
                            <i class="fas fa-car mr-1"></i>
                            <span class="mr-3">Sedan</span>
                            <i class="fas fa-gas-pump mr-1"></i>
                            <span>Electric</span>
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
                        <button onclick="toggleModal('loginModal')" class="w-full bg-rental-primary hover:bg-blue-700 text-white py-2 rounded-lg transition text-sm">
                            Rent Now
                        </button>
                    </div>
                </div>

                <!-- Car Card 2 -->
                <div class="car-card bg-gray-800 rounded-xl overflow-hidden border border-gray-700 transition-all duration-300 hover:border-rental-primary">
                    <div class="relative h-48 bg-gray-700 flex items-center justify-center p-4">
                        <img src="assets/images/isuzu.jpg" 
                             alt="Audi Q5" 
                             class="h-full object-contain">
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-white">Isuzu</h3>
                            <div class="text-rental-accent font-bold">P1500<span class="text-gray-400 text-sm">/day</span></div>
                        </div>
                        <div class="flex items-center text-gray-400 text-xs mb-3">
                            <i class="fas fa-car mr-1"></i>
                            <span class="mr-3">SUV</span>
                            <i class="fas fa-gas-pump mr-1"></i>
                            <span>Petrol</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center text-xs mb-3">
                            <div class="bg-gray-700 py-2 rounded">
                                <i class="fas fa-user-friends text-gray-400"></i>
                                <p class="mt-1">5 Seats</p>
                            </div>
                            <div class="bg-gray-700 py-2 rounded">
                                <i class="fas fa-suitcase text-gray-400"></i>
                                <p class="mt-1">4 Bags</p>
                            </div>
                            <div class="bg-gray-700 py-2 rounded">
                                <i class="fas fa-tachometer-alt text-gray-400"></i>
                                <p class="mt-1">Auto</p>
                            </div>
                        </div>
                        <button onclick="toggleModal('loginModal')"  class="w-full bg-rental-primary hover:bg-blue-700 text-white py-2 rounded-lg transition text-sm">
                            Rent Now
                        </button>
                    </div>
                </div>

                <!-- Car Card 3 -->
                <div class="car-card bg-gray-800 rounded-xl overflow-hidden border border-gray-700 transition-all duration-300 hover:border-rental-primary">
                    <div class="relative h-48 bg-gray-700 flex items-center justify-center p-4">
                        <img src="assets/images/sorento.jpg" 
                             alt="Mercedes AMG" 
                             class="h-full object-contain">
                        <div class="absolute top-4 right-4 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                            Limited
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-white">Sorento</h3>
                            <div class="text-rental-accent font-bold">P1500<span class="text-gray-400 text-sm">/day</span></div>
                        </div>
                        <div class="flex items-center text-gray-400 text-xs mb-3">
                            <i class="fas fa-car mr-1"></i>
                            <span class="mr-3">Coupe</span>
                            <i class="fas fa-gas-pump mr-1"></i>
                            <span>Petrol</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center text-xs mb-3">
                            <div class="bg-gray-700 py-2 rounded">
                                <i class="fas fa-user-friends text-gray-400"></i>
                                <p class="mt-1">2 Seats</p>
                            </div>
                            <div class="bg-gray-700 py-2 rounded">
                                <i class="fas fa-suitcase text-gray-400"></i>
                                <p class="mt-1">2 Bags</p>
                            </div>
                            <div class="bg-gray-700 py-2 rounded">
                                <i class="fas fa-tachometer-alt text-gray-400"></i>
                                <p class="mt-1">Auto</p>
                            </div>
                        </div>
                        <button onclick="toggleModal('loginModal')" class="w-full bg-rental-primary hover:bg-blue-700 text-white py-2 rounded-lg transition text-sm">
                            Rent Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="relative z-10 py-12 px-4 bg-gray-900 bg-opacity-50">
        <div class="container mx-auto">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-white mb-2">Why Choose <span class="text-rental-primary">ArnieRentaCar</span></h2>
                <p class="max-w-2xl mx-auto text-sm">We provide the best car rental experience</p>
            </div>

            <div class="space-y-4">
                <div class="bg-gray-800 bg-opacity-70 p-4 rounded-xl border border-gray-700 hover:border-rental-primary transition">
                    <div class="w-12 h-12 bg-rental-primary bg-opacity-20 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-shield-alt text-rental-primary text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Full Insurance</h3>
                    <p class="text-gray-400 text-sm">All our rentals include comprehensive insurance coverage for your peace of mind.</p>
                </div>

                <div class="bg-gray-800 bg-opacity-70 p-4 rounded-xl border border-gray-700 hover:border-rental-primary transition">
                    <div class="w-12 h-12 bg-rental-primary bg-opacity-20 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-headset text-rental-primary text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">24/7 Support</h3>
                    <p class="text-gray-400 text-sm">Our customer service team is available around the clock to assist you.</p>
                </div>

                <div class="bg-gray-800 bg-opacity-70 p-4 rounded-xl border border-gray-700 hover:border-rental-primary transition">
                    <div class="w-12 h-12 bg-rental-primary bg-opacity-20 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-car-side text-rental-primary text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Wide Selection</h3>
                    <p class="text-gray-400 text-sm">Choose from our diverse fleet of premium vehicles for every need.</p>
                </div>
            </div>
        </div>
    </section>

    
      <!-- Footer -->
    <footer class="relative z-10 bg-gray-900 bg-opacity-90 border-t border-gray-800 py-8 px-4">
        <div class="container mx-auto">
            <div class="space-y-8">
                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        <i class="fas fa-car text-rental-primary text-xl"></i>
                        <span class="text-lg font-bold text-white">Arnie<span class="text-rental-primary">RentaCar</span></span>
                    </div>
                    <p class="text-gray-400 text-sm mb-3">Premium car rental service with the best vehicles and customer experience.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-rental-primary transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-rental-primary transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-rental-primary transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-md font-semibold text-white mb-3">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 text-sm hover:text-rental-primary transition">Home</a></li>
                            <li><a href="#" class="text-gray-400 text-sm hover:text-rental-primary transition">Vehicles</a></li>
                            <li><a href="#" class="text-gray-400 text-sm hover:text-rental-primary transition">Locations</a></li>
                            <li><a href="#" class="text-gray-400 text-sm hover:text-rental-primary transition">Pricing</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-md font-semibold text-white mb-3">Support</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 text-sm hover:text-rental-primary transition">FAQs</a></li>
                            <li><a href="#" class="text-gray-400 text-sm hover:text-rental-primary transition">Contact Us</a></li>
                            <li><a href="#" class="text-gray-400 text-sm hover:text-rental-primary transition">Privacy Policy</a></li>
                            <li><a href="#" class="text-gray-400 text-sm hover:text-rental-primary transition">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>

                <div>
                    <h3 class="text-md font-semibold text-white mb-3">Newsletter</h3>
                    <p class="text-gray-400 text-sm mb-3">Subscribe to get special offers and updates</p>
                    <div class="flex">
                        <input type="email" placeholder="Your email" class="bg-gray-700 text-white px-3 py-2 text-sm rounded-l-lg w-full focus:outline-none focus:ring-2 focus:ring-rental-primary">
                        <button class="bg-rental-primary hover:bg-blue-700 text-white px-3 py-2 rounded-r-lg transition text-sm">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-6 pt-6 text-center text-gray-400 text-sm">
                <p>&copy; 2023 ArnieRent a Car. All rights reserved.</p>
            </div>
        </div>
    </footer>

  <!-- Login Modal -->
  <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-end justify-center z-50 hidden" 
       onclick="closeOnOutsideClick(event, 'loginModal')">
    <div class="modal-content bg-white p-6 w-full max-w-md rounded-t-2xl" 
         role="dialog" 
         aria-modal="true" 
         onclick="event.stopPropagation()">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-dark">Welcome Back</h2>
        <button onclick="toggleModal('loginModal')" class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <form action="auth/login.php" method="POST" class="space-y-4">
        <div>
          <label class="block text-black text-sm mb-1">Email Address</label>
          <input type="email" name="email" placeholder="Enter your email" 
                 class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm text-black">
        </div>
        <div>
          <label class="block text-black text-sm mb-1">Password</label>
          <input type="password" name="password" placeholder="Enter your password" 
                 class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm text-black">
        </div>
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input type="checkbox" id="remember" class="h-4 w-4 text-primary rounded focus:ring-primary">
            <label for="remember" class="ml-2 text-gray-700 text-sm">Remember me</label>
          </div>
          <a href="#" class="text-sm text-primary hover:underline font-medium">Forgot password?</a>
        </div>
        <button type="submit" 
                class="w-full bg-rental-primary hover:bg-blue-700 text-white py-3 rounded-lg font-medium text-sm mt-4">
          Login to Your Account
        </button>
      </form>
      <div class="mt-4 text-center">
        <p class="text-gray-600 text-sm">Don't have an account? 
          <a href="#" onclick="toggleModal('registerModal'); toggleModal('loginModal')" class="text-primary hover:underline font-medium">Sign up now</a>
        </p>
      </div>
    </div>
  </div>

  <!-- Register Modal -->
  <div id="registerModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-end justify-center z-50 hidden" 
       onclick="closeOnOutsideClick(event, 'registerModal')">
    <div class="modal-content bg-white p-6 w-full max-w-md rounded-t-2xl" 
         role="dialog" 
         aria-modal="true" 
         onclick="event.stopPropagation()">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-dark">Create Account</h2>
        <button onclick="toggleModal('registerModal')" class="text-black hover:text-gray-700">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <form action="auth/register.php" method="POST" class="space-y-4">
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-black text-sm mb-1">First Name</label>
            <input type="text" name="firstname" placeholder="Firstname" 
                   class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm text-black" required>
          </div>
          <div>
            <label class="block text-black text-sm mb-1">Last Name</label>
            <input type="text" name="lastname" placeholder="Lastname" 
                   class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm text-black" required>
          </div>
        </div>
        <div>
          <label class="block text-black text-sm mb-1">Email Address</label>
          <input type="email" name="email" placeholder="Email" 
                 class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm text-black" required>
        </div>
        <div>
          <label class="block text-black text-sm mb-1">Password</label>
          <input type="password" name="password" placeholder="Create password" 
                 class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm text-black" required>
        </div>
        <div>
          <label class="block text-black text-sm mb-1">Confirm Password</label>
          <input type="password" name="confirm" placeholder="Confirm password" 
                 class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm text-black" required>
        </div>
        <div class="flex items-start">
          <input type="checkbox" id="terms" class="h-4 w-4 text-primary rounded mt-1 focus:ring-primary" required>
          <label for="terms" class="ml-2 text-gray-700 text-xs">
            I agree to the <a href="#" class="text-primary hover:underline font-medium">Terms</a> and <a href="#" class="text-primary hover:underline font-medium">Privacy Policy</a>
          </label>
        </div>
        <button type="submit" 
                class="w-full bg-rental-primary hover:bg-blue-700 text-white py-3 rounded-lg font-medium text-sm mt-2">
          Create Account
        </button>
      </form>
      <div class="mt-4 text-center">
        <p class="text-gray-600 text-sm">Already have an account? 
          <a href="#" onclick="toggleModal('loginModal'); toggleModal('registerModal')" class="text-primary hover:underline font-medium">Login here</a>
        </p>
      </div>
    </div>
  </div>
</body>
</html>