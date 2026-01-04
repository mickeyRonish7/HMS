<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Browse Available Rooms') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-green-800 dark:text-green-200">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                             <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800 dark:text-red-200">{{ __('There were problems with your request') }}</h3>
                            <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Filter Bar -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm mb-8 flex flex-wrap items-center justify-between gap-4 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-bold text-gray-500 uppercase tracking-wider">{{ __('Filters') }}:</span>
                    <div class="flex bg-gray-100 dark:bg-gray-900 rounded-lg p-1">
                        <a href="{{ route('student.rooms.browse') }}" class="px-4 py-1.5 rounded-md text-sm font-bold {{ !request('type') ? 'bg-white dark:bg-gray-800 shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                            {{ __('All') }}
                        </a>
                        <a href="{{ route('student.rooms.browse', ['type' => 'standard']) }}" class="px-4 py-1.5 rounded-md text-sm font-bold {{ request('type') == 'standard' ? 'bg-white dark:bg-gray-800 shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                            {{ __('Standard') }}
                        </a>
                        <a href="{{ route('student.rooms.browse', ['type' => 'deluxe']) }}" class="px-4 py-1.5 rounded-md text-sm font-bold {{ request('type') == 'deluxe' ? 'bg-white dark:bg-gray-800 shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                            {{ __('Deluxe') }}
                        </a>
                    </div>
                </div>

                <div class="text-sm text-gray-500 font-medium">
                    {{ __('Showing') }} <span class="font-bold text-gray-900 dark:text-white">{{ $rooms->count() }}</span> {{ __('Rooms') }}
                </div>
            </div>

            <!-- Rooms Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($rooms as $room)
                    <div class="group bg-white dark:bg-gray-800 rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 flex flex-col">
                        <!-- Image Container -->
                        <div class="relative h-64 overflow-hidden">
                            @if($room->room_photo)
                                <img src="{{ asset('storage/' . $room->room_photo) }}" alt="Room {{ $room->room_number }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-gray-100 dark:bg-gray-700 flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                    <span class="text-xs font-bold uppercase">{{ __('No Photo Available') }}</span>
                                </div>
                            @endif
                            
                            <!-- Badges -->
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                <span class="px-3 py-1 bg-white/90 dark:bg-gray-900/90 backdrop-blur shadow-sm rounded-full text-[10px] font-black uppercase tracking-widest text-blue-600">
                                    {{ $room->type }}
                                </span>
                                @if($room->availableBedsCount() > 0)
                                    <span class="px-3 py-1 bg-green-500/90 backdrop-blur shadow-sm rounded-full text-[10px] font-black uppercase tracking-widest text-white">
                                        {{ $room->availableBedsCount() }} {{ __('Available') }}
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-500/90 backdrop-blur shadow-sm rounded-full text-[10px] font-black uppercase tracking-widest text-white">
                                        {{ __('Full') }}
                                    </span>
                                @endif
                            </div>

                            <div class="absolute bottom-4 left-4">
                                <h3 class="text-2xl font-black text-white drop-shadow-lg">{{ __('Room') }} {{ $room->room_number }}</h3>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex-grow flex flex-col">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Capacity') }}</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $room->capacity }} {{ __('Beds') }}</span>
                                    </div>
                                    <div class="w-px h-8 bg-gray-100 dark:bg-gray-700"></div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Assigned') }}</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $room->beds->where('is_occupied', true)->count() }} {{ __('Students') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Features -->
                            <div class="flex flex-wrap gap-2 mb-6">
                                <span class="flex items-center text-[10px] font-bold text-gray-500 px-2 py-1 bg-gray-50 dark:bg-gray-900 rounded-md">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Wi-Fi
                                </span>
                                <span class="flex items-center text-[10px] font-bold text-gray-500 px-2 py-1 bg-gray-50 dark:bg-gray-900 rounded-md">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    24/7 Water
                                </span>
                                @if($room->type === 'deluxe')
                                    <span class="flex items-center text-[10px] font-bold text-gray-500 px-2 py-1 bg-gray-50 dark:bg-gray-900 rounded-md">
                                        <svg class="w-3 h-3 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        {{ __('Air Condition') }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('student.rooms.show', $room->id) }}" class="mt-auto block w-full py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-center font-bold rounded-2xl hover:bg-black dark:hover:bg-gray-100 transition-colors shadow-lg shadow-gray-200 dark:shadow-none">
                                {{ __('View Details') }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="bg-gray-50 dark:bg-gray-800 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('No Rooms Found') }}</h3>
                        <p class="text-gray-500 dark:text-gray-400 mt-2">{{ __('Try adjusting your filters to find more options.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
