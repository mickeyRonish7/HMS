<x-app-layout>
    <x-slot name="header">
        Assign Room - {{ $student->name }}
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Select an Available Bed</h3>

            @if($errors->any())
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.students.assign-room.post', $student->id) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    @foreach($availableBeds->groupBy('room_id') as $roomId => $beds)
                        @php $room = $beds->first()->room; @endphp
                        <div class="border border-gray-300 rounded-lg p-4 hover:border-blue-500 transition">
                            <h4 class="font-bold text-gray-900 mb-2">Room {{ $room->room_number }}</h4>
                            <p class="text-sm text-gray-600 mb-3">{{ ucfirst($room->type) }}</p>
                            
                            <div class="space-y-2">
                                @foreach($beds as $bed)
                                    <label class="flex items-center p-2 border rounded hover:bg-blue-50 cursor-pointer">
                                        <input type="radio" name="bed_id" value="{{ $bed->id }}" required class="mr-2">
                                        <span class="text-sm">Bed {{ $bed->bed_number }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($availableBeds->isEmpty())
                    <p class="text-center text-gray-500 py-8">No available beds at the moment.</p>
                @else
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.students.index') }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">Assign Room</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</x-app-layout>
