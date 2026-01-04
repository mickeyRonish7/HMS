<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Submit Feedback') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('student.feedback.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Facility Ratings') }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Please rate the following hostel facilities from 1 to 5 stars.') }}</p>
                        </div>

                        <!-- Room Rating -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Room/Living Conditions') }}</label>
                            <div class="flex items-center space-x-1" x-data="{ rating: 0 }">
                                <template x-for="i in 5">
                                    <button type="button" @click="rating = i" class="focus:outline-none transition-transform hover:scale-110">
                                        <svg :class="rating >= i ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'" class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                        </svg>
                                    </button>
                                </template>
                                <input type="hidden" name="room_rating" :value="rating" required>
                            </div>
                        </div>

                        <!-- Mess Rating -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Mess/Food Services') }}</label>
                            <div class="flex items-center space-x-1" x-data="{ rating: 0 }">
                                <template x-for="i in 5">
                                    <button type="button" @click="rating = i" class="focus:outline-none transition-transform hover:scale-110">
                                        <svg :class="rating >= i ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'" class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                        </svg>
                                    </button>
                                </template>
                                <input type="hidden" name="mess_rating" :value="rating" required>
                            </div>
                        </div>

                        <!-- Security Rating -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Security & Discipline') }}</label>
                            <div class="flex items-center space-x-1" x-data="{ rating: 0 }">
                                <template x-for="i in 5">
                                    <button type="button" @click="rating = i" class="focus:outline-none transition-transform hover:scale-110">
                                        <svg :class="rating >= i ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'" class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                        </svg>
                                    </button>
                                </template>
                                <input type="hidden" name="security_rating" :value="rating" required>
                            </div>
                        </div>

                        <!-- Comments -->
                        <div class="mb-6">
                            <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Detailed Feedback / Suggestions') }}</label>
                            <textarea id="comment" name="comment" rows="4" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="{{ __('What could we do better?') }}"></textarea>
                        </div>

                        <!-- Anonymous Toggle -->
                        <div class="mb-8">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_anonymous" value="1" class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Submit Anonymously') }}</span>
                            </label>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-14">{{ __('If enabled, your name will not be visible to administrators.') }}</p>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-lg font-semibold text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 dark:focus:bg-indigo-600 active:bg-indigo-900 dark:active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Submit Feedback') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
