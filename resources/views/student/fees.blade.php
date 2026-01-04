<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fees & Payments') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showPayment: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($fees->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            No fee records found.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($fees as $fee)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($fee->type) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap font-bold">${{ number_format($fee->amount, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($fee->due_date)->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($fee->status == 'paid')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                                @else
                                                     <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Pending</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($fee->status == 'pending')
                                                    <button @click="showPayment = true" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1 px-3 rounded text-xs ml-2">
                                                        Pay Now
                                                    </button>
                                                @else
                                                    <span class="text-green-600 font-bold">Paid</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Payment Modal (Simulation) -->
                        <div x-show="showPayment" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                            <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showPayment = false">
                                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                </div>
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Select Payment Method</h3>
                                                <div class="mt-4 grid grid-cols-2 gap-4">
                                                    <button class="flex flex-col items-center p-4 border rounded hover:bg-green-50 hover:border-green-500">
                                                        <span class="font-bold text-green-600">eSewa</span>
                                                        <span class="text-xs text-gray-500">Digital Wallet</span>
                                                    </button>
                                                     <button class="flex flex-col items-center p-4 border rounded hover:bg-purple-50 hover:border-purple-500">
                                                        <span class="font-bold text-purple-600">Khalti</span>
                                                        <span class="text-xs text-gray-500">Digital Wallet</span>
                                                    </button>
                                                     <button class="flex flex-col items-center p-4 border rounded hover:bg-red-50 hover:border-red-500">
                                                        <span class="font-bold text-red-600">Global IME</span>
                                                        <span class="text-xs text-gray-500">Mobile Banking</span>
                                                    </button>
                                                     <button class="flex flex-col items-center p-4 border rounded hover:bg-yellow-50 hover:border-yellow-500">
                                                        <span class="font-bold text-yellow-600">Kumari Bank</span>
                                                        <span class="text-xs text-gray-500">Net Banking</span>
                                                    </button>
                                                     <button class="flex flex-col items-center p-4 border rounded hover:bg-blue-50 hover:border-blue-500 col-span-2">
                                                        <span class="font-bold text-blue-600">PayPal</span>
                                                        <span class="text-xs text-gray-500">International</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="button" @click="showPayment = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
