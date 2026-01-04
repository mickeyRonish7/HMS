<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Visitor Management') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ rejectModal: false, currentVisitorId: null, rejectionReason: '', charCount: 0 }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Section 1: Specific Visit Requests (Purpose & Date) -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ __('Recent Visit Requests') }}
                </h3>
                
                @if($visitRequests->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No specific visit requests found.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Visitor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Purpose</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($visitRequests as $visit)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white font-medium">{{ $visit->visitor_name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $visit->purpose }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($visit->status === 'approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Approved</span>
                                        @elseif($visit->status === 'rejected')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Rejected</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($visit->status === 'pending')
                                            <div class="flex space-x-3">
                                                <form action="{{ route('student.visits.approve', $visit->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Approve</button>
                                                </form>
                                                <form action="{{ route('student.visits.reject', $visit->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Reject</button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-gray-400">Processed</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Section 2: Visitor Account Registrations (New Users) -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    {{ __('Visitor Account Approvals') }}
                </h3>
                
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif

                @if($visitors->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No visitor accounts awaiting registration approval.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">My Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Admin Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($visitors as $visitor)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $visitor->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $visitor->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $visitor->phone }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($visitor->rejected_by === 'student')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400" title="{{ $visitor->rejection_reason }}">Rejected</span>
                                        @elseif($visitor->student_approved)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Approved</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($visitor->admin_approved)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Approved</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($visitor->student_approved)
                                            <span class="text-gray-400">Approved</span>
                                        @elseif($visitor->rejected_by === 'student')
                                            <div class="flex space-x-2">
                                                <form action="{{ route('student.visitors.approve', $visitor->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Re-Approve</button>
                                                </form>
                                                <button @click="rejectionReason = '{{ addslashes($visitor->rejection_reason ?? '') }}'; charCount = rejectionReason.length; currentVisitorId = {{ $visitor->id }}; rejectModal = true" class="text-gray-600 hover:text-gray-900">Reason</button>
                                            </div>
                                        @else
                                            <div class="flex space-x-3">
                                                <form action="{{ route('student.visitors.approve', $visitor->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 font-bold">Approve</button>
                                                </form>
                                                <button @click="rejectionReason = ''; charCount = 0; currentVisitorId = {{ $visitor->id }}; rejectModal = true" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-bold">Reject</button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Rejection Modal (for Account Approvals) -->
        <template x-teleport="body">
            <div x-show="rejectModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                    <div x-show="rejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click="rejectModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                    <div x-show="rejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form :action="`/student/visitors/${currentVisitorId}/reject`" method="POST">
                            @csrf
                            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white mb-4">Reject Visitor Account</h3>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                                    <textarea x-model="rejectionReason" @input="charCount = rejectionReason.length" name="rejection_reason" rows="4" maxlength="500" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" placeholder="Minimum 10 characters required..."></textarea>
                                    <div class="flex justify-between mt-1">
                                        <p class="text-xs text-gray-500">Min 10 chars</p>
                                        <p class="text-xs" :class="charCount < 10 ? 'text-red-500' : 'text-gray-500'"><span x-text="charCount"></span>/500</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" :disabled="charCount < 10" :class="charCount < 10 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-red-700'" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white sm:ml-3 sm:w-auto sm:text-sm">Reject Account</button>
                                <button type="button" @click="rejectModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>
