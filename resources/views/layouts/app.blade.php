<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ auth()->check() ? (auth()->user()->theme === 'dark' ? 'dark' : '') : '' }} {{ auth()->check() ? 'font-' . auth()->user()->font_size : 'font-medium' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hostel Management') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        figtree: ['Figtree', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Styles for Dark Mode and Font Sizes -->
    <style>
        /* Dark Mode Styles */
        .dark {
            color-scheme: dark;
        }
        
        /* Font Size Classes */
        .font-small {
            font-size: 14px;
        }
        .font-medium {
            font-size: 16px;
        }
        .font-large {
            font-size: 18px;
        }
        
        /* Smooth transitions */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
    </style>
    
    <!-- Theme Initialization Script -->
    <script>
        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            @auth
                const theme = '{{ auth()->user()->theme ?? 'light' }}';
                const fontSize = '{{ auth()->user()->font_size ?? 'medium' }}';
                
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                }
                
                document.documentElement.classList.add('font-' + fontSize);
            @endauth
        });
    </script>
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-900 font-figtree">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100 dark:bg-gray-900">
        
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 md:translate-x-0 md:static md:inset-0 shadow-lg">
            <div class="flex items-center justify-center h-16 bg-blue-600 dark:bg-blue-700 border-b border-blue-500 dark:border-blue-600">
                <span class="text-white font-bold uppercase text-xl tracking-wider">HMS Portal</span>
            </div>
            
            <div class="p-4">
                <div class="flex items-center gap-3 mb-6 p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    @if(Auth::user()->profile_photo_path)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="h-10 w-10 rounded-full object-cover">
                    @else
                        <div class="h-10 w-10 rounded-full bg-blue-200 dark:bg-blue-700 flex items-center justify-center text-blue-700 dark:text-blue-300 font-bold text-lg">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ Auth::user()->role }}</p>
                    </div>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('dashboard') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                        <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        {{ __('Dashboard') }}
                    </a>
                    
                    @if(Auth::user()->role === 'student')
                        <a href="{{ route('student.rooms.browse') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('student.rooms.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            {{ __('Browse Rooms') }}
                        </a>
                        <a href="{{ route('student.room') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('student.room') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            {{ __('My Room') }}
                        </a>
                        <a href="{{ route('student.id-card') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('student.id-card') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            {{ __('ID Card') }}
                        </a>
                        <a href="{{ route('student.feedback.create') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('student.feedback.create') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            {{ __('Feedback') }}
                        </a>
                        <a href="{{ route('student.visitors.pending') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('student.visitors.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            {{ __('Pending Visitors') }}
                        </a>
                        <a href="{{ route('student.fees') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('student.fees') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ __('Fees') }} & {{ __('Payments') }}
                        </a>
                        <a href="{{ route('student.attendance') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('student.attendance') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                             <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            {{ __('Attendance') }}
                        </a>
                        <a href="{{ route('student.profile') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('student.profile') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                             <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ __('Profile') }}
                        </a>
                    @elseif(Auth::user()->role === 'admin')
                        <!-- Admin Navigation Items -->
                         <a href="{{ route('admin.rooms.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('admin.rooms.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            {{ __('Rooms') }}
                        </a>
                        <a href="{{ route('admin.room-requests.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('admin.room-requests.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            {{ __('Room Requests') }}
                        </a>
                        <a href="{{ route('admin.attendance.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('admin.attendance.index') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            {{ __('Attendance') }}
                        </a>
                        <a href="{{ route('admin.students.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('admin.students.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            {{ __('Students') }}
                        </a>
                        <a href="{{ route('admin.feedback.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('admin.feedback.*') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            {{ __('Feedback') }}
                        </a>
                        <a href="{{ route('admin.complaints.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('admin.complaints.index') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                             <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            {{ __('Complaints') }}
                        </a>
                        <a href="{{ route('admin.notices.index') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('admin.notices.index') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            {{ __('Notices') }}
                        </a>
                        <a href="{{ route('admin.visitors.requests') }}" class="flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg transition-colors group {{ request()->routeIs('admin.visitors.requests') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}">
                             <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            {{ __('Visit Requests') }}
                        </a>
                    @elseif(Auth::user()->role === 'visitor')
                        {{-- Visitor has no additional nav items beyond Dashboard --}}
                    @endif


                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-600 dark:hover:text-red-400 rounded-lg transition-colors group">
                            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            {{ __('Logout') }}
                        </button>
                    </form>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="flex justify-between items-center py-4 px-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 dark:text-gray-400 focus:outline-none md:hidden">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white ml-4">
                        {{ isset($header) ? $header : __('Dashboard') }}
                    </h2>
                </div>

                <div class="flex items-center space-x-2">
                    <!-- Theme Toggle -->
                    <x-theme-toggle />
                    
                    <!-- Font Size Control -->
                    <x-font-size-control />
                    
                    <!-- Language Switcher -->
                    <x-language-switcher />
                    
                    <!-- Notifications -->
                    <x-notification-bell />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900 p-6">
                {{ $slot }}
            </main>
        </div>
        
        @auth
            @include('components.chatbot')
        @endauth
    </div>

    @stack('scripts')
</body>
</html>
