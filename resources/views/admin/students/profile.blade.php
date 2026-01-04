<x-app-layout>
    <x-slot name="header">
        Student Profile
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 p-6">
            <div class="flex items-start gap-6">
                <div class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-3xl flex-shrink-0">
                    {{ substr($student->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-semibold text-gray-900">{{ $student->name }}</h2>
                    <p class="text-sm text-gray-600">{{ $student->email }}</p>
                    <p class="text-sm text-gray-600 mt-1">Phone: {{ $student->phone ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">Department: {{ $student->department ?? 'N/A' }}</p>
                </div>
                <div class="text-right">
                    <a href="{{ route('admin.students.index') }}" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">Back to Students</a>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <div class="bg-gray-50 rounded p-4 border border-gray-100">
                        <h3 class="text-lg font-medium text-gray-900">Academic & Assignment</h3>
                        <p class="text-sm text-gray-600 mt-2">Year {{ $student->year ?? 'N/A' }} | Sem {{ $student->semester ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600 mt-2">Parent Contact: {{ $student->parent_phone ?? 'N/A' }}</p>

                        <div class="mt-4">
                            <h4 class="text-sm text-gray-500">Room Assignment</h4>
                            @if($student->bed)
                                <p class="text-sm font-medium text-green-700">Room {{ $student->bed->room->room_number }} - Bed {{ $student->bed->bed_number }}</p>
                            @else
                                <p class="text-sm font-medium text-red-600">Not Assigned</p>
                                <a href="{{ route('admin.students.assign-room', $student->id) }}" class="text-xs text-blue-600 hover:underline">Assign Room</a>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 bg-white rounded p-4 border border-gray-100">
                        <h3 class="text-lg font-medium text-gray-900">Fees</h3>
                        @if($student->fees && $student->fees->count())
                            <ul class="mt-3 space-y-2">
                                @foreach($student->fees as $fee)
                                    <li class="flex justify-between text-sm">
                                        <span>{{ $fee->description ?? 'Fee' }} ({{ $fee->month ?? '' }})</span>
                                        <span class="font-medium">{{ number_format($fee->amount, 2) }} - <span class="text-xs {{ $fee->status === 'paid' ? 'text-green-600' : 'text-red-600' }}">{{ ucfirst($fee->status) }}</span></span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500 mt-2">No fee records found.</p>
                        @endif
                    </div>

                    <div class="mt-6 bg-white rounded p-4 border border-gray-100">
                        <h3 class="text-lg font-medium text-gray-900">Complaints</h3>
                        @if($student->complaints && $student->complaints->count())
                            <ul class="mt-3 space-y-2">
                                @foreach($student->complaints as $complaint)
                                    <li class="text-sm border-l-4 pl-3 py-2 border-gray-200">
                                        <div class="flex justify-between">
                                            <span>{{ $complaint->title }}</span>
                                            <span class="text-xs text-gray-500">{{ $complaint->created_at->format('Y-m-d') }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">{{ $complaint->description }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500 mt-2">No complaints found.</p>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="bg-white rounded p-4 border border-gray-100">
                        <h3 class="text-lg font-medium text-gray-900">Quick Info</h3>
                        <p class="text-sm text-gray-600 mt-2">Email: {{ $student->email }}</p>
                        <p class="text-sm text-gray-600">Phone: {{ $student->phone ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">Registered: {{ $student->created_at->format('Y-m-d') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
