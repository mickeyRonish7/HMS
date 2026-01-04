<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Profile Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             @if(session('success'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4" role="alert">
                    <p class="font-bold">{{ __('Success') }}</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Profile Card -->
                <div class="md:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 text-center border border-gray-100 dark:border-gray-700">
                         <div class="relative inline-block">
                             @if($user->profile_photo_path)
                                <img class="h-32 w-32 rounded-3xl mx-auto object-cover border-4 border-gray-100 dark:border-gray-700 shadow-lg" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}">
                            @else
                                <div class="h-32 w-32 rounded-3xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-500 dark:text-indigo-400 text-4xl font-black mx-auto border-4 border-gray-100 dark:border-gray-700 shadow-md">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <h3 class="mt-4 text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $user->name }}</h3>
                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">{{ $user->email }}</p>
                        <div class="mt-6 flex justify-center">
                             <span class="px-4 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-lg text-[10px] font-black uppercase tracking-widest">{{ __('Administrator') }}</span>
                        </div>
                    </div>

                    <!-- Stats Card -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-700">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('System Role') }}</span>
                                <span class="text-xs font-bold text-gray-900 dark:text-white">{{ ucfirst($user->role) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('Member Since') }}</span>
                                <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $user->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Form -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-8 border border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-8 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ __('Admin Information') }}
                        </h3>
                        
                        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Full Name') }}</label>
                                    <input type="text" value="{{ $user->name }}" readonly class="w-full bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-700 rounded-xl shadow-sm text-gray-500 dark:text-gray-400 cursor-not-allowed text-sm font-bold">
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Phone Number') }}</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm font-bold">
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Profile Photo') }}</label>
                                    <input type="file" name="profile_photo" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-400 hover:file:bg-indigo-100 transition-all">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Office Address') }}</label>
                                    <textarea name="address" rows="3" class="w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm font-bold">{{ old('address', $user->address) }}</textarea>
                                </div>

                                <div class="md:col-span-2 pt-4 flex justify-end">
                                    <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-black uppercase tracking-widest transition shadow-lg">
                                        {{ __('Save Admin Changes') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password Form -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-8 border border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-8 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            {{ __('Security & Password') }}
                        </h3>
                        
                        <form action="{{ route('user.password.secure_update', [], false) }}" method="POST">
                            @csrf
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Current Password') }}</label>
                                    <input type="password" name="current_password" class="w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                     @error('current_password', 'updatePassword')
                                        <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('New Password') }}</label>
                                    <input type="password" name="password" class="w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                     @error('password', 'updatePassword')
                                        <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Confirm New Password') }}</label>
                                    <input type="password" name="password_confirmation" class="w-full border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div class="md:col-span-2 pt-4 flex justify-end">
                                    <button type="submit" class="px-8 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl text-xs font-black uppercase tracking-widest transition shadow-lg">
                                        {{ __('Update Security Credentials') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
