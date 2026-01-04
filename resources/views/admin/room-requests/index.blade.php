<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Room Occupancy Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Student') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Requested Room') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Current Assignment') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Requested At') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($requests as $request)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($request->user)
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        @if($request->user->profile_photo_path)
                                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $request->user->profile_photo_path) }}" alt="">
                                                        @else
                                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">
                                                                {{ substr($request->user->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $request->user->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $request->user->email }}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-sm text-red-500 font-bold">{{ __('User Deleted') }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold dark:text-gray-300">
                                            @if($request->room)
                                                <div class="flex items-center">
                                                    @if($request->room->room_photo)
                                                        <img class="h-10 w-10 rounded-lg object-cover mr-3" src="{{ asset('storage/' . $request->room->room_photo) }}" alt="Room">
                                                    @else
                                                        <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                                                            <span class="text-[8px] font-bold text-gray-400">NO IMG</span>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        Room {{ $request->room->room_number }}
                                                        <span class="block text-[10px] font-medium text-gray-400 font-black uppercase tracking-widest mt-1">{{ $request->room->type }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-red-500 font-bold text-xs">{{ __('Room Deleted') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @if($request->user && $request->user->bed)
                                                @if($request->user->bed->room)
                                                    Room {{ $request->user->bed->room->room_number }} (Bed {{ $request->user->bed->bed_number }})
                                                @else
                                                    Bed {{ $request->user->bed->bed_number }} (Room Deleted)
                                                @endif
                                            @else
                                                <span class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ __('Unassigned') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-black uppercase tracking-widest rounded-full 
                                                {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $request->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                            ">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $request->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($request->status === 'pending')
                                                <div x-data="{ open: false, rejectOpen: false }" class="flex items-center justify-end space-x-2">
                                                    <!-- Approve Button -->
                                                    <button @click="open = true" class="text-green-600 hover:text-green-900 dark:hover:text-green-400 font-bold border border-green-200 dark:border-green-800 px-3 py-1 rounded-md transition-colors">{{ __('Approve') }}</button>
                                                    
                                                    <!-- Reject Button -->
                                                    <button @click="rejectOpen = true" class="text-red-600 hover:text-red-900 dark:hover:text-red-400 font-bold border border-red-200 dark:border-red-800 px-3 py-1 rounded-md transition-colors">{{ __('Reject') }}</button>

                                                    <!-- Approve Modal -->
                                                    <template x-teleport="body">
                                                        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                                            <div class="flex items-center justify-center min-h-screen p-4">
                                                                <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/90 backdrop-blur-sm" @click="open = false"></div>
                                                                <div x-show="open" x-transition.scale class="relative bg-white dark:bg-gray-800 rounded-2xl p-8 max-w-md w-full text-left shadow-2xl">
                                                                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-4">{{ __('Approve Room Assignment') }}</h3>
                                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 font-medium">
                                                                        {{ __('This student will be automatically assigned to the first available bed in Room') }} <span class="font-bold text-blue-600">{{ $request->room ? $request->room->room_number : 'Unknown' }}</span>.
                                                                    </p>
                                                                    <form action="{{ route('admin.room-requests.approve', $request->id) }}" method="POST">
                                                                        @csrf
                                                                        <div class="flex space-x-3">
                                                                            <button type="submit" class="flex-grow py-3 bg-blue-600 text-white font-black rounded-xl uppercase tracking-widest">{{ __('Confirm Assignment') }}</button>
                                                                            <button type="button" @click="open = false" class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-xl">{{ __('Cancel') }}</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>

                                                    <!-- Reject Modal -->
                                                    <template x-teleport="body">
                                                        <div x-show="rejectOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                                            <div class="flex items-center justify-center min-h-screen p-4">
                                                                <div x-show="rejectOpen" x-transition.opacity class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/90 backdrop-blur-sm" @click="rejectOpen = false"></div>
                                                                <div x-show="rejectOpen" x-transition.scale class="relative bg-white dark:bg-gray-800 rounded-2xl p-8 max-w-md w-full text-left shadow-2xl">
                                                                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-4">{{ __('Reject Room Request') }}</h3>
                                                                    <form action="{{ route('admin.room-requests.reject', $request->id) }}" method="POST">
                                                                        @csrf
                                                                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Reason for Rejection') }}</label>
                                                                        <textarea name="admin_note" rows="3" class="w-full rounded-xl border-gray-100 dark:border-gray-700 dark:bg-gray-900 text-sm mb-6" placeholder="{{ __('e.g. This room is reserved for graduating seniors.') }}"></textarea>
                                                                        <div class="flex space-x-3">
                                                                            <button type="submit" class="flex-grow py-3 bg-red-600 text-white font-black rounded-xl uppercase tracking-widest">{{ __('Reject Request') }}</button>
                                                                            <button type="button" @click="rejectOpen = false" class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-xl">{{ __('Cancel') }}</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-600 text-xs font-medium">{{ __('Processed') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-20 text-center text-gray-500 dark:text-gray-400">
                                            {{ __('No pending room requests found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
