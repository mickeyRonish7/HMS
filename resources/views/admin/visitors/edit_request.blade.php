<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Visit Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.visitors.update_request', $request->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Visitor Name -->
                            <div>
                                <label for="visitor_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Visitor Name</label>
                                <input type="text" name="visitor_name" id="visitor_name" value="{{ old('visitor_name', $request->visitor_name) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                @error('visitor_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Visitor Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Visitor Phone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $request->phone) }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Purpose -->
                            <div class="md:col-span-2">
                                <label for="purpose" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Purpose</label>
                                <textarea name="purpose" id="purpose" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">{{ old('purpose', $request->purpose) }}</textarea>
                                @error('purpose') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Host Student (Display Only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Host Student</label>
                                <div class="mt-1 block w-full p-2 bg-gray-100 dark:bg-gray-700 rounded-md">
                                    {{ $request->student->name ?? 'Unknown' }}
                                </div>
                            </div>

                            <!-- Visit Date -->
                            <div>
                                <label for="visit_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Visit Date</label>
                                <input type="date" name="visit_date" id="visit_date" value="{{ old('visit_date', $request->visit_date ? \Carbon\Carbon::parse($request->visit_date)->format('Y-m-d') : '') }}" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                @error('visit_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                                    <option value="pending" {{ $request->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $request->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $request->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('admin.visitors.requests') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                {{ __('Update Request') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
