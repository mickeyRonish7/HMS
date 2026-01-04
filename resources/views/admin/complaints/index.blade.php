<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Complaints') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($complaints->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            No active complaints.
                        </div>
                    @else
                        <div class="grid gap-6">
                            @foreach($complaints as $complaint)
                                <div class="border rounded-lg p-4 {{ $complaint->status == 'pending' ? 'bg-red-50 border-red-200' : 'bg-gray-50' }}">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center mb-1">
                                                <span class="font-bold text-lg mr-2">{{ ucfirst($complaint->category) }} Issue</span>
                                                <span class="text-xs text-gray-500">by {{ $complaint->student->name }} • {{ $complaint->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-gray-700 mb-2">{{ $complaint->description }}</p>
                                             @if($complaint->admin_remark)
                                                <div class="mt-2 text-sm bg-white p-2 rounded border border-gray-200">
                                                    <span class="font-bold text-gray-600">Admin Remark:</span> {{ $complaint->admin_remark }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <form action="{{ route('admin.complaints.update', $complaint) }}" method="POST" class="flex flex-col space-y-2">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="text-sm border-gray-300 rounded shadow-sm">
                                                    <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                    <option value="rejected" {{ $complaint->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                                <input type="text" name="admin_remark" placeholder="Remark..." class="text-sm border-gray-300 rounded shadow-sm" value="{{ $complaint->admin_remark }}">
                                                <button type="submit" class="bg-blue-600 text-white text-xs px-2 py-1 rounded hover:bg-blue-700">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
