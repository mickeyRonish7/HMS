<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Room') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Room Number</label>
                        <input type="text" name="room_number" required class="mt-1 block w-full rounded border-gray-300">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Capacity</label>
                        <input type="number" name="capacity" min="1" required class="mt-1 block w-full rounded border-gray-300">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="type" class="mt-1 block w-full rounded border-gray-300">
                            <option value="standard">Standard</option>
                            <option value="deluxe">Deluxe</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Room Photo</label>
                        <input type="file" name="room_photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-500">Upload a photo of the room (optional)</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Create Room</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
