<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Student Feedback') }}
            </h2>
            <a href="{{ route('admin.feedback.analytics') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                {{ __('View Analytics') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Filters -->
                    <form action="{{ route('admin.feedback.index') }}" method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                            <select name="status" onchange="this.form.submit()" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>{{ __('Reviewed') }}</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>{{ __('Resolved') }}</option>
                            </select>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Student') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Ratings') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Comment') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($feedbacks as $feedback)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($feedback->is_anonymous)
                                                <span class="text-gray-500 italic">{{ __('Anonymous') }}</span>
                                            @else
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $feedback->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $feedback->user->email }}</div>
                                            @endif
                                            <div class="text-xs text-gray-400 mt-1">{{ $feedback->created_at->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs space-y-1">
                                                <div class="flex items-center">
                                                    <span class="w-16">{{ __('Room') }}:</span>
                                                    <span class="text-yellow-500">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            {{ $feedback->room_rating >= $i ? '★' : '☆' }}
                                                        @endfor
                                                    </span>
                                                </div>
                                                <div class="flex items-center">
                                                    <span class="w-16">{{ __('Mess') }}:</span>
                                                    <span class="text-yellow-500">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            {{ $feedback->mess_rating >= $i ? '★' : '☆' }}
                                                        @endfor
                                                    </span>
                                                </div>
                                                <div class="flex items-center">
                                                    <span class="w-16">{{ __('Security') }}:</span>
                                                    <span class="text-yellow-500">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            {{ $feedback->security_rating >= $i ? '★' : '☆' }}
                                                        @endfor
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-900 dark:text-gray-300 line-clamp-2 max-w-xs" title="{{ $feedback->comment }}">
                                                {{ $feedback->comment }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $feedback->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $feedback->status === 'reviewed' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $feedback->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                                            ">
                                                {{ ucfirst($feedback->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <form action="{{ route('admin.feedback.status', $feedback->id) }}" method="POST">
                                                    @csrf
                                                    <select name="status" onchange="this.form.submit()" class="text-xs rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 py-1">
                                                        <option value="pending" {{ $feedback->status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                                        <option value="reviewed" {{ $feedback->status == 'reviewed' ? 'selected' : '' }}>{{ __('Reviewed') }}</option>
                                                        <option value="resolved" {{ $feedback->status == 'resolved' ? 'selected' : '' }}>{{ __('Resolved') }}</option>
                                                    </select>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">{{ __('No feedback received yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $feedbacks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
