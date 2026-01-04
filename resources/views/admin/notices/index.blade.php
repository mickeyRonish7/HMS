<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notice Board') }}
            </h2>
            <a href="{{ route('admin.notices.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Post Notice
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($notices as $notice)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold mb-2">{{ $notice->title }}</h3>
                                <p class="text-xs text-gray-500 mb-4">
                                    Posted on {{ $notice->created_at->format('M d, Y') }} • For: {{ ucfirst($notice->audience) }}
                                </p>
                            </div>
                            <form action="{{ route('admin.notices.destroy', $notice) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                        <p class="text-gray-700 whitespace-pre-line mb-3">{{ $notice->content }}</p>
                        @if($notice->attachment)
                            <a href="{{ Storage::url($notice->attachment) }}" target="_blank" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                View Attachment
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="col-span-2 text-center text-gray-500 py-10">
                        No notices posted yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
