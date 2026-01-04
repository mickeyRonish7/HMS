<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit ID Card Details') }} - {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.students.id-card.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- ID Number -->
                            <div class="col-span-2">
                                <label for="student_id_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Student ID Number') }}</label>
                                <input type="text" name="student_id_number" id="student_id_number" value="{{ old('student_id_number', $user->student_id_number) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. HMS-2024-001">
                            </div>

                            <!-- Blood Group -->
                            <div>
                                <label for="blood_group" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Blood Group') }}</label>
                                <select name="blood_group" id="blood_group" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">{{ __('Select') }}</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                        <option value="{{ $bg }}" {{ old('blood_group', $user->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Issued Date -->
                            <div>
                                <label for="id_card_issued_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Issued Date') }}</label>
                                <input type="date" name="id_card_issued_date" id="id_card_issued_date" value="{{ old('id_card_issued_date', $user->id_card_issued_date ? \Carbon\Carbon::parse($user->id_card_issued_date)->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Expiry Date -->
                            <div>
                                <label for="id_card_expiry_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Expiry Date') }}</label>
                                <input type="date" name="id_card_expiry_date" id="id_card_expiry_date" value="{{ old('id_card_expiry_date', $user->id_card_expiry_date ? \Carbon\Carbon::parse($user->id_card_expiry_date)->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Emergency Contact Name -->
                            <div>
                                <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Emergency Contact Name') }}</label>
                                <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. John Doe">
                            </div>

                            <!-- Emergency Contact Phone -->
                            <div>
                                <label for="emergency_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Emergency Contact Phone') }}</label>
                                <input type="text" name="emergency_contact" id="emergency_contact" value="{{ old('emergency_contact', $user->emergency_contact) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. +977 98XXXXXXXX">
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Update ID Card Details') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
