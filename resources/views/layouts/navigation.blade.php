<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">
                        HMS
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition">
                        Dashboard
                    </a>
                    @if(Auth::check() && Auth::user()->role == 'admin')
                        <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
                            Rooms
                        </a>
                        <a href="{{ route('admin.users.pending') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
                            Students (Pending)
                        </a>
                        <a href="{{ route('admin.attendance.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
                            Attendance
                        </a>
                         <a href="{{ route('admin.profile') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
                            Profile
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <!-- User Name -->
                    <div class="text-gray-500 font-medium text-sm">
                        {{ Auth::user()->name }}
                    </div>
                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="ml-4">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-semibold">
                            Log Out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 underline mr-4">Log in</a>
                    <a href="{{ route('register') }}" class="text-sm text-gray-700 underline">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
