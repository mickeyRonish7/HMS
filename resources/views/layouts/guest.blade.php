<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
<body class="antialiased text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
        <!-- Background Image with Blur -->
        <div class="absolute inset-0 z-0">
             <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80" alt="Background">
             <div class="absolute inset-0 bg-blue-900 opacity-50 mix-blend-multiply backdrop-blur-sm"></div>
        </div>

        <!-- Logo -->
        <div class="relative z-10 mb-6">
            <a href="/" class="flex flex-col items-center">
                <div class="bg-white p-3 rounded-full shadow-lg mb-2">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <span class="text-3xl font-bold text-white tracking-wider drop-shadow-md">HMS</span>
            </a>
        </div>

        <!-- Card Container -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-xl relative z-10 border border-gray-100">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
