<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Room') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Room Number</label>
                        <input type="text" name="room_number" value="{{ $room->room_number }}" required class="mt-1 block w-full rounded border-gray-300">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Capacity</label>
                        <input type="number" name="capacity" value="{{ $room->capacity }}" min="1" required class="mt-1 block w-full rounded border-gray-300">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="type" class="mt-1 block w-full rounded border-gray-300">
                            <option value="standard" {{ $room->type == 'standard' ? 'selected' : '' }}>Standard</option>
                            <option value="deluxe" {{ $room->type == 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full rounded border-gray-300">
                            <option value="active" {{ $room->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Room Photo</label>
                        
                        @if($room->room_photo)
                            <div class="mt-2 mb-3">
                                <img src="{{ asset('storage/' . $room->room_photo) }}" alt="Current room photo" class="w-48 h-32 object-cover rounded-lg shadow">
                                <p class="text-xs text-gray-500 mt-1">Current photo</p>
                            </div>
                        @endif
                        
                        <input type="file" name="room_photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-500">Upload a new photo to replace the current one (optional)</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Update Room</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
