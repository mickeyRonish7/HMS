<x-app-layout>
    <x-slot name="header">
        Student Management
    </x-slot>

    <div class="max-w-7xl mx-auto">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6">
            @foreach($students as $student)
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <!-- Student Info -->
                        <div class="md:col-span-2">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 h-16 w-16">
                                    @if($student->profile_photo_path)
                                        <img class="h-16 w-16 rounded-full object-cover border-2 border-indigo-100" src="{{ asset('storage/' . $student->profile_photo_path) }}" alt="{{ $student->name }}">
                                    @else
                                        <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $student->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $student->email }}</p>
                                    
                                    <div class="mt-3 grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs text-gray-500">Academic Info</p>
                                            <p class="text-sm font-medium text-gray-900">
                                                Year {{ $student->year }} | Sem {{ $student->semester }}
                                            </p>
                                            <p class="text-sm text-gray-600">{{ $student->department }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Room Assignment</p>
                                            @if($student->bed)
                                                <p class="text-sm font-medium text-green-700">
                                                    Room {{ $student->bed->room->room_number }} - Bed {{ $student->bed->bed_number }}
                                                </p>
                                                <div class="flex gap-2 mt-1">
                                                    <a href="{{ route('admin.students.assign-room', $student->id) }}" class="text-xs text-blue-600 hover:underline border-r border-gray-300 pr-2">Change Room</a>
                                                    
                                                    <form action="{{ route('admin.students.unassign', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this student from their room?');">
                                                        @csrf
                                                        <button type="submit" class="text-xs text-red-600 hover:underline">Remove Room</button>
                                                    </form>
                                                </div>
                                            @else
                                                <p class="text-sm font-medium text-red-600">Not Assigned</p>
                                                <a href="{{ route('admin.students.assign-room', $student->id) }}" class="text-xs text-blue-600 hover:underline">Assign Room</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Parent Contact Card -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                <h4 class="text-sm font-bold text-gray-900">Parent Contact</h4>
                            </div>
                            <p class="text-sm text-gray-700 font-medium">{{ $student->parent_phone }}</p>
                            <p class="text-xs text-gray-500 mt-1">WhatsApp Enabled</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 pt-4 border-t border-gray-200 flex justify-end gap-3 items-center">
                        <div class="mr-auto flex gap-3 text-xs font-medium text-gray-500">
                            <a href="{{ route('admin.students.id-card.show', $student->id) }}" class="hover:text-indigo-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                                View ID
                            </a>
                            <a href="{{ route('admin.students.id-card.edit', $student->id) }}" class="hover:text-indigo-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                Edit ID
                            </a>
                        </div>

                        <a href="{{ route('admin.students.profile', $student->id) }}" class="text-sm text-blue-600 hover:text-blue-900 font-medium">View Full Profile</a>
                        @if(!$student->bed)
                            <a href="{{ route('admin.students.assign-room', $student->id) }}" class="text-sm bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded transition">Assign Room</a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
