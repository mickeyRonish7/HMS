<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Hostel Management') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased text-gray-800 bg-white">

    <!-- Navigation -->
    <nav x-data="{ open: false }" class="absolute w-full z-20 top-0 left-0 bg-blue-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <!-- Logo -->
                <div class="flex justify-start">
                    <a href="#" class="flex items-center">
                         <!-- Icon -->
                         <div class="bg-blue-600 p-2 rounded-lg mr-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                         </div>
                        <span class="text-2xl font-bold text-white tracking-wide">HMS</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#features" class="text-gray-200 hover:text-white transition font-medium">Features</a>
                    <a href="#rules" class="text-gray-200 hover:text-white transition font-medium">Rules</a>
                    <a href="#contact" class="text-gray-200 hover:text-white transition font-medium">Contact</a>
                    
                    @if (Route::has('login'))
                        @auth
                            <!-- <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-white text-blue-700 font-bold rounded-full hover:bg-gray-100 transition shadow-lg">Dashboard</a> -->
                        @else
                            <a href="{{ route('login') }}" class="text-white hover:text-gray-200 font-medium transition mr-4">Log in</a>
                            <a href="{{ route('register') }}" class="px-5 py-2 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-500 transition shadow-lg border border-blue-500">Get Started</a>
                        @endauth
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="open = !open" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" @click.away="open = false" class="md:hidden absolute top-0 inset-x-0 p-2 transition transform origin-top-right">
            <div class="rounded-lg shadow-md bg-white ring-1 ring-black ring-opacity-5 overflow-hidden">
                <div class="px-5 pt-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-blue-600">HMS</span>
                    </div>
                    <div class="-mr-2">
                        <button @click="open = false" type="button" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="#features" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Features</a>
                    <a href="#rules" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Rules</a>
                    <a href="#contact" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">Contact</a>
                </div>
                <div class="px-5 py-4 border-t border-gray-100">
                     @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700">Dashboard</a>
                        @else
                             <a href="{{ route('login') }}" class="block text-center w-full px-4 py-2 border border-blue-600 rounded-md shadow-sm text-base font-medium text-blue-600 bg-white hover:bg-blue-50 mb-2">Log in</a>
                            <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700">Sign up</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative bg-gray-900 overflow-hidden">
        <div class="absolute inset-0">
            <img class="w-full h-full object-cover opacity-40" src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80" alt="Hostel Building">
            <div class="absolute inset-0 bg-blue-900 opacity-40 mix-blend-multiply"></div>
        </div>
        <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8 flex flex-col items-start justify-center min-h-[80vh]">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-7xl mb-6 drop-shadow-lg">
                Your Home <br> <span class="text-blue-400">Away From Home</span>
            </h1>
            <p class="mt-6 text-xl text-gray-300 max-w-3xl drop-shadow-md">
                Experience a secure, comfortable, and vibrant living environment designed for your academic success. Managing your hostel life has never been easier.
            </p>
            <div class="mt-10 max-w-sm sm:flex sm:max-w-none w-full sm:justify-start gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10 transition shadow-xl transform hover:-translate-y-1">
                    Student Registration
                </a>
                <a href="{{ route('login') }}" class="mt-3 sm:mt-0 px-8 py-3 border border-white text-base font-medium rounded-full text-white bg-transparent hover:bg-white hover:text-gray-900 md:py-4 md:text-lg md:px-10 transition shadow-xl">
                    Admin / Staff Login
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Facilities</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Everything you need to live comfortably
                </p>
            </div>

            <div class="mt-20">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="flex flex-col items-center p-8 bg-white rounded-xl shadow-md hover:shadow-xl transition">
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 text-blue-600 mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Modern Rooms</h3>
                        <p class="text-center text-gray-500">Spacious, well-ventilated rooms with comfortable furniture and ample storage.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex flex-col items-center p-8 bg-white rounded-xl shadow-md hover:shadow-xl transition">
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-100 text-green-600 mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Secure & Safe</h3>
                        <p class="text-center text-gray-500">24/7 security surveillance, biometric entry logs, and visitor management.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex flex-col items-center p-8 bg-white rounded-xl shadow-md hover:shadow-xl transition">
                        <div class="flex items-center justify-center h-16 w-16 rounded-full bg-purple-100 text-purple-600 mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">High-Speed Wi-Fi</h3>
                        <p class="text-center text-gray-500">Uninterrupted internet access for your studies and entertainment needs.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rules/Info Section -->
    <div id="rules" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <img class="rounded-lg shadow-2xl" src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Students Studying">
            </div>
            <div class="md:w-1/2 md:pl-12">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl mb-6">
                     Community Guidelines
                </h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center border border-blue-200 mt-1">
                            <span class="text-blue-600 text-xs font-bold">1</span>
                        </div>
                        <p class="ml-4 text-lg text-gray-600">Respect quiet hours from 10 PM to 6 AM.</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center border border-blue-200 mt-1">
                            <span class="text-blue-600 text-xs font-bold">2</span>
                        </div>
                        <p class="ml-4 text-lg text-gray-600">Visitors must register at the front desk and leave by 7 PM.</p>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center border border-blue-200 mt-1">
                            <span class="text-blue-600 text-xs font-bold">3</span>
                        </div>
                        <p class="ml-4 text-lg text-gray-600">Keep common areas clean and report maintenance issues promptly.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800" id="contact">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-gray-400">
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">Hostel Management System</h3>
                    <p class="text-sm">
                        Providing a home away from home for students. Secure, comfortable, and conducive to learning.
                    </p>
                </div>
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white">Home</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white">Student Login</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white">Admin Login</a></li>
                    </ul>
                </div>
                <div>
                     <h3 class="text-white text-lg font-bold mb-4">Contact Us</h3>
                     <p class="text-sm mb-2">Manmohan memorial politechinc,morang,nepal</p>
                     <p class="text-sm mb-2">Phone: +977 9821736251</p>
                     <p class="text-sm">Email: mtulsi480@gmail.com</p>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-8 text-center">
                <p class="text-base text-gray-400">&copy; {{ date('Y') }} Group of tulsi mandal. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Chatbot Component (Visible if configured for guest, or keep hidden for auth only) -->
    <!-- Ideally chatbot is for auth users only based on current implementation, but can be enabled for guests as 'visitor' context later -->

</body>
</html>
