<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Room Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between mb-6">
                    <h3 class="text-lg font-bold">All Rooms</h3>
                    <a href="{{ route('admin.rooms.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded transition">Add Room</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($rooms as $room)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow {{ $room->status == 'maintenance' ? 'border-red-300' : '' }}">
                        <!-- Room Photo -->
                        <div class="h-48 bg-gray-200 overflow-hidden">
                            @if($room->room_photo)
                                <img src="{{ Storage::url($room->room_photo) }}" alt="Room {{ $room->room_number }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-indigo-200">
                                    <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                            @endif
                        </div>

                        <!-- Room Info -->
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-bold text-xl text-gray-900">Room {{ $room->room_number }}</h4>
                                <span class="text-xs px-3 py-1 rounded-full font-semibold {{ $room->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($room->status) }}
                                </span>
                            </div>
                            
                            <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Capacity: {{ $room->capacity }}
                                </span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                                    {{ ucfirst($room->type) }}
                                </span>
                            </div>
                            
                            <!-- Bed Status -->
                            <div class="mb-4">
                                <h5 class="text-sm font-semibold mb-2 text-gray-700">Beds:</h5>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach($room->beds as $bed)
                                    <div class="text-xs p-2 border rounded text-center {{ $bed->is_occupied ? 'bg-red-50 border-red-300 text-red-700' : 'bg-green-50 border-green-300 text-green-700' }}">
                                        {{ $bed->bed_number }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex justify-end gap-2 pt-2 border-t">
                                <a href="{{ route('admin.rooms.edit', $room->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
