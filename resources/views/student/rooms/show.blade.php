<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('student.rooms.browse') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Room Details') }}
            </h2>
        </div>
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

            <div class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden shadow-xl border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col lg:flex-row">
                    <!-- Photo Gallery / Main Image -->
                    <div class="lg:w-3/5 relative min-h-[400px]">
                        @if($room->room_photo)
                            <img src="{{ asset('storage/' . $room->room_photo) }}" class="absolute inset-0 w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="absolute top-6 left-6 grid grid-cols-1 gap-3">
                            <span class="px-4 py-2 bg-white/90 dark:bg-gray-900/90 backdrop-blur rounded-2xl text-xs font-black uppercase tracking-widest text-blue-600 shadow-lg">{{ $room->type }}</span>
                            @if($room->status !== 'active')
                                <span class="px-4 py-2 bg-red-600/90 backdrop-blur rounded-2xl text-xs font-black uppercase tracking-widest text-white shadow-lg">{{ __('Maintenance') }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Details and Booking -->
                    <div class="lg:w-2/5 p-8 lg:p-12 flex flex-col">
                        <div class="mb-10">
                            <h3 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-2">{{ __('Room Number') }}</h3>
                            <h1 class="text-5xl font-black text-gray-900 dark:text-white">{{ $room->room_number }}</h1>
                        </div>

                        <div class="grid grid-cols-2 gap-8 mb-10">
                            <div>
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Total Capacity') }}</h4>
                                <p class="text-xl font-bold dark:text-white">{{ $room->capacity }} {{ __('Beds') }}</p>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ __('Current Availability') }}</h4>
                                <p class="text-xl font-bold {{ $room->availableBedsCount() > 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $room->availableBedsCount() }} {{ __('Vacant') }}
                                </p>
                            </div>
                        </div>

                        <!-- Amenities List -->
                        <div class="mb-10">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">{{ __('Room Amenities') }}</h4>
                            <div class="grid grid-cols-2 gap-y-3">
                                @php
                                    $amenities = [
                                        ['icon' => 'wifi', 'label' => 'Hi-Speed WiFi'],
                                        ['icon' => 'lightning-bolt', 'label' => '24/7 Power'],
                                        ['icon' => 'archive', 'label' => 'Personal Locker'],
                                        ['icon' => 'beaker', 'label' => 'Water Purifier'],
                                    ];
                                    if($room->type === 'deluxe') {
                                        $amenities[] = ['icon' => 'sun', 'label' => 'Air Conditioning'];
                                        $amenities[] = ['icon' => 'desktop-computer', 'label' => 'Study Station'];
                                    }
                                @endphp
                                @foreach($amenities as $amenity)
                                    <div class="flex items-center text-sm font-semibold text-gray-600 dark:text-gray-300">
                                        <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                                            <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        {{ $amenity['label'] }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if(Auth::user()->bed && Auth::user()->bed->room_id == $room->id)
                            <div class="mt-auto bg-green-50 dark:bg-green-900/20 p-6 rounded-2xl border border-green-100 dark:border-green-800 text-center">
                                <p class="text-green-800 dark:text-green-300 font-bold mb-1">{{ __('This is your currently assigned room.') }}</p>
                                <p class="text-xs text-green-600 dark:text-green-400">{{ __('Bed Number') }}: {{ Auth::user()->bed->bed_number }}</p>
                            </div>
                        @elseif($room->availableBedsCount() > 0 && $room->status === 'active' && Auth::user()->role === 'student')
                            <div x-data="{ open: false }" class="mt-auto">
                                <button @click="open = true" class="w-full py-5 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-blue-200 dark:shadow-none uppercase tracking-widest flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                    {{ __('Request This Room') }}
                                </button>

                                <!-- Modal -->
                                <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 transition-opacity" aria-hidden="true">
                                            <div class="absolute inset-0 bg-gray-500/75 dark:bg-gray-900/90 backdrop-blur-sm"></div>
                                        </div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-8 border border-gray-100 dark:border-gray-700">
                                            <form action="{{ route('student.rooms.request') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                                
                                                <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">{{ __('Confirm Room Request') }}</h3>
                                                <p class="text-sm text-gray-500 mb-6 font-medium">{{ __('You are requesting to be assigned to') }} <span class="font-bold text-blue-600">Room {{ $room->room_number }}</span>. {{ __('Administrators will review your request shortly.') }}</p>

                                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Optional Message to Admin') }}</label>
                                                <textarea name="student_message" rows="3" class="w-full rounded-2xl border-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500 mb-8" placeholder="{{ __('e.g. I would prefer a top bunk if possible.') }}"></textarea>

                                                <div class="flex flex-col sm:flex-row gap-3">
                                                    <button type="submit" class="flex-grow py-4 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 transition-all uppercase tracking-widest shadow-lg shadow-blue-200 dark:shadow-none">
                                                        {{ __('Submit Request') }}
                                                    </button>
                                                    <button type="button" @click="open = false" class="sm:w-32 py-4 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                                                        {{ __('Cancel') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mt-auto bg-gray-50 dark:bg-gray-700/50 p-6 rounded-2xl border border-gray-100 dark:border-gray-600 text-center opacity-75">
                                <p class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest text-sm">{{ __('Currently Unavailable') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- More Rooms Preview (Optional) -->
        </div>
    </div>
</x-app-layout>
