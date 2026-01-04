<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fee Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($fees as $fee)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $fee->student->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${{ number_format($fee->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $fee->due_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $fee->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($fee->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($fee->type) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.fees.show', $fee->id) }}" class="text-indigo-600 hover:text-indigo-900">Receipt</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $fees->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
