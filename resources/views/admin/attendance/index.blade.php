<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Attendance Management') }}
            </h2>
            <a href="{{ route('admin.attendance.export') }}" class="ml-4 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export PDF
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Manual Entry System -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Manual Entry / Biometric Simulation</h3>
                <form action="{{ route('admin.attendance.store') }}" method="POST" class="flex items-end gap-4">
                    @csrf
                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Student ID/Name</label>
                        <select name="student_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @foreach(\App\Models\User::where('role', 'student')->where('is_approved', true)->get() as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} (ID: {{ $student->id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-1/4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Type</label>
                        <select name="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="in">Check IN</option>
                            <option value="out">Check OUT</option>
                        </select>
                    </div>
                    <div class="w-1/4">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Log Attendance
                        </button>
                    </div>
                </form>
            </div>

            <!-- List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($attendances as $row)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $row->student->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-green-600 font-bold">{{ $row->time_in ? \Carbon\Carbon::parse($row->time_in)->format('h:i A') : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-red-600 font-bold">{{ $row->time_out ? \Carbon\Carbon::parse($row->time_out)->format('h:i A') : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ ucfirst($row->status) }}
                                        </span>
                                        @if($row->student->parent_phone && $row->created_at->diffInMinutes(now()) < 60)
                                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                                Sent
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
