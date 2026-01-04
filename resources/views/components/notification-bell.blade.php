@props(['user' => auth()->user()])

@php
    $unreadCount = $user ? $user->unreadNotifications()->count() : 0;
    $recentNotifications = $user ? $user->notifications()->latest()->take(5)->get() : collect();
@endphp

<div 
    x-data="{ 
        open: false, 
        unreadCount: {{ $unreadCount }},
        markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').content
                }
            }).then(() => {
                if (this.unreadCount > 0) {
                    this.unreadCount--;
                }
            });
        },
        markAllAsRead() {
            fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').content
                }
            }).then(() => {
                this.unreadCount = 0;
                this.open = false;
                location.reload();
            });
        }
    }" 
    class="relative"
>
    <button 
        @click="open = !open" 
        @click.away="open = false"
        class="relative flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
        aria-label="Notifications"
    >
        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        <!-- Unread Badge -->
        <span 
            x-show="unreadCount > 0" 
            x-text="unreadCount > 99 ? '99+' : unreadCount"
            class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full min-w-[1.25rem]"
        ></span>
    </button>

    <!-- Dropdown -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50"
        style="display: none;"
    >
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Notifications') }}</h3>
            <template x-if="unreadCount > 0">
                <button 
                    @click="markAllAsRead()"
                    class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium"
                >
                    {{ __('Mark All as Read') }}
                </button>
            </template>
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($recentNotifications as $notification)
                <a 
                    href="{{ $notification->action_url ?? route('notifications.index') }}"
                    @click="markAsRead({{ $notification->id }})"
                    class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-b border-gray-100 dark:border-gray-700 {{ $notification->read_at ? 'opacity-60' : 'bg-indigo-50 dark:bg-indigo-900/20' }}"
                >
                    <div class="flex items-start">
                        <!-- Icon based on type -->
                        <div class="flex-shrink-0 mt-0.5">
                            @if($notification->type === 'late_return')
                                <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @elseif($notification->type === 'room_request')
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                </div>
                            @elseif($notification->type === 'fee')
                                <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @elseif($notification->type === 'visitor')
                                <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $notification->title }}
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5 line-clamp-2">
                                {{ $notification->message }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <!-- Unread indicator -->
                        @if(!$notification->read_at)
                            <div class="flex-shrink-0 ml-2">
                                <span class="inline-block w-2 h-2 bg-indigo-600 rounded-full"></span>
                            </div>
                        @endif
                    </div>
                </a>
            @empty
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('No notifications') }}</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        @if($recentNotifications->count() > 0)
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                <a 
                    href="{{ route('notifications.index') }}" 
                    class="block text-center text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium"
                >
                    {{ __('View All') }}
                </a>
            </div>
        @endif
    </div>
</div>
