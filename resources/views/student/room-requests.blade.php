<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Room Requests') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Room') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Submitted At') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-black text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Admin Notes') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($requests as $request)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($request->room->room_photo)
                                                    <img class="h-10 w-10 rounded-lg object-cover mr-3" src="{{ asset('storage/' . $request->room->room_photo) }}" alt="Room">
                                                @else
                                                    <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                                                        <span class="text-[8px] font-bold text-gray-400">NO IMG</span>
                                                    </div>
                                                @endif
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    Room {{ $request->room->room_number }}
                                                    <span class="ml-2 text-[10px] bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-600 dark:text-gray-300">{{ $request->room->type }}</span>
                                                </div>
                                            </div>
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
                                            {{ $request->created_at->format('M d, Y H:i') }}
                                            <span class="text-xs text-gray-400">({{ $request->created_at->diffForHumans() }})</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $request->admin_notes ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-20 text-center text-gray-500 dark:text-gray-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.414.414l5 5a1 1 0 01.414 1.414V19a2 2 0 01-2 2z"></path></svg>
                                                <p class="font-medium">{{ __('No room requests found.') }}</p>
                                                <a href="{{ route('student.rooms.browse') }}" class="mt-4 text-blue-600 hover:text-blue-500 font-bold hover:underline">
                                                    {{ __('Browse Rooms') }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
