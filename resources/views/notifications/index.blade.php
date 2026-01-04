<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Notifications') }}
            </h2>
            @if($notifications->where('read_at', null)->count() > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-indigo-600 dark:text-indigo-400 font-medium hover:underline">
                        {{ __('Mark all as read') }}
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($notifications as $notification)
                        <div class="p-6 transition-colors {{ $notification->read_at ? 'opacity-75' : 'bg-indigo-50/50 dark:bg-indigo-900/10' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        @if($notification->type === 'late_return')
                                            <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @elseif($notification->type === 'room_request')
                                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $notification->title }}</h3>
                                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $notification->message }}</p>
                                        <div class="flex items-center space-x-4 mt-3">
                                            <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                            @if($notification->action_url)
                                                <a href="{{ $notification->action_url }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline uppercase tracking-wider">
                                                    {{ __('View Details') }} &rarr;
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" title="{{ __('Mark as read') }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="{{ __('Delete') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-20 text-center">
                            <div class="bg-gray-50 dark:bg-gray-700/50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('No Notifications') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 mt-2">{{ __('You\'re all caught up! Check back later for updates.') }}</p>
                        </div>
                    @endforelse
                </div>
                @if($notifications->hasPages())
                    <div class="p-6 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
