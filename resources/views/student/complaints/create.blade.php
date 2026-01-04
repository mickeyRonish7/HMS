<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Report an Issue') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('student.complaints.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                        <select name="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="Electrical">Electrical</option>
                            <option value="Plumbing">Plumbing</option>
                            <option value="Furniture">Furniture</option>
                            <option value="Cleanliness">Cleanliness</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                        <textarea name="description" rows="5" placeholder="Describe your issue in detail..." class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Submit Complaint
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
