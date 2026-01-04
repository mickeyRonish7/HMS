<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Visitor Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Add Visitor Form -->
                <form action="{{ route('admin.visitors.store') }}" method="POST" class="mb-8 p-4 border rounded bg-gray-50">
                    @csrf
                    <h3 class="font-bold mb-4">Log New Visitor</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Student ID</label>
                            <input type="number" name="student_id" required class="mt-1 block w-full rounded border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Visitor Name</label>
                            <input type="text" name="visitor_name" required class="mt-1 block w-full rounded border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="phone" class="mt-1 block w-full rounded border-gray-300">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Log Entry</button>
                        </div>
                    </div>
                </form>

                <!-- Visitors List -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visitor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entry Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exit Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($visitors as $visitor)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $visitor->visitor_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $visitor->student->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $visitor->entry_time }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $visitor->exit_time ?? 'Active' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(!$visitor->exit_time)
                                    <form action="{{ route('admin.visitors.update', $visitor->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Log Exit</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
