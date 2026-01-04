<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Digital Hostel ID Card') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8 items-start">
                
                <!-- ID Card Preview (Front) -->
                <div class="w-full md:w-1/2">
                    <div class="bg-gradient-to-br from-indigo-600 to-blue-700 bg-[length:400%_400%] animate-gradient rounded-2xl shadow-2xl p-1 text-white overflow-hidden">
                        <div class="bg-white dark:bg-gray-900 rounded-[15px] p-6 text-gray-900 dark:text-white relative">
                            <!-- Watermark / Logo -->
                            <div class="absolute top-0 right-0 p-4 opacity-10">
                                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                            </div>

                            <!-- Header -->
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-blue-600 p-2 rounded-lg">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                                    </div>
                                    <div>
                                        <h1 class="text-lg font-bold tracking-tight uppercase leading-none">{{ config('app.name') }}</h1>
                                        <p class="text-[10px] text-gray-500 uppercase font-semibold mt-1 tracking-wider">{{ __('Student ID Card') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">{{ $user->role }}</span>
                                </div>
                            </div>

                            <!-- Content Area -->
                            <div class="flex items-start">
                                <!-- Photo -->
                                <div class="flex-shrink-0 mr-6">
                                    <div class="relative">
                                        @if($user->profile_photo_path)
                                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" class="w-28 h-28 object-cover rounded-xl border-4 border-blue-50 dark:border-gray-800 shadow-lg">
                                        @else
                                            <div class="w-28 h-28 bg-gray-100 dark:bg-gray-800 rounded-xl flex items-center justify-center border-4 border-blue-50 dark:border-gray-800 shadow-lg">
                                                <svg class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                                            </div>
                                        @endif
                                        <div class="absolute -bottom-2 -right-2 bg-blue-600 w-8 h-8 rounded-full border-2 border-white dark:border-gray-900 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path></svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="flex-grow space-y-3">
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                                        <p class="text-xs text-blue-600 font-bold mt-0.5">{{ $user->student_id_number ?? 'ID: HMS-'.str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-y-3 pt-2">
                                        <div>
                                            <p class="text-[9px] uppercase font-bold text-gray-400 dark:text-gray-500">{{ __('Dept / Program') }}</p>
                                            <p class="text-xs font-semibold uppercase">{{ $user->department ?? __('N/A') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9px] uppercase font-bold text-gray-400 dark:text-gray-500">{{ __('Year / Sem') }}</p>
                                            <p class="text-xs font-semibold">{{ $user->year ?? '' }} {{ $user->semester ?? '' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9px] uppercase font-bold text-gray-400 dark:text-gray-500">{{ __('Blood Group') }}</p>
                                            <p class="text-xs font-semibold text-red-600">{{ $user->blood_group ?? __('N/A') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[9px] uppercase font-bold text-gray-400 dark:text-gray-500">{{ __('Expiry Date') }}</p>
                                            <p class="text-xs font-semibold">{{ $user->id_card_expiry_date ? \Carbon\Carbon::parse($user->id_card_expiry_date)->format('M Y') : 'Valid till Graduation' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer / QR -->
                            <div class="mt-8 pt-6 border-t border-dashed border-gray-200 dark:border-gray-700 flex items-end justify-between">
                                <div class="flex flex-col space-y-2">
                                    @if($user->bed)
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                            <span class="text-[10px] font-bold text-gray-600 dark:text-gray-400">Room {{ $user->bed->room->room_number }} - Bed {{ $user->bed->bed_number }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        <span class="text-[10px] font-bold text-gray-600 dark:text-gray-400">{{ $user->phone ?? __('N/A') }}</span>
                                    </div>
                                </div>
                                
                                <div class="bg-white p-1 rounded-lg">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('admin.students.profile', $user->id)) }}" class="w-16 h-16">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info and Actions -->
                <div class="w-full md:w-1/2 space-y-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 p-6 rounded-2xl">
                        <h3 class="text-blue-900 dark:text-blue-200 font-bold mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            {{ __('Digital ID Benefits') }}
                        </h3>
                        <ul class="text-sm text-blue-800 dark:text-blue-300 space-y-2 mt-4 ml-2">
                            <li class="flex items-start">
                                <span class="text-blue-400 mr-2">●</span>
                                {{ __('Fast identification for mess and security.') }}
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-400 mr-2">●</span>
                                {{ __('Quick access to your profile via QR code.') }}
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-400 mr-2">●</span>
                                {{ __('Always accessible from your mobile phone.') }}
                            </li>
                        </ul>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <a href="{{ route('student.id-card.download') }}" class="flex items-center justify-center space-x-2 px-6 py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-2xl font-bold hover:bg-gray-800 dark:hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl group">
                            <svg class="w-6 h-6 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>{{ __('Download PDF Version') }}</span>
                        </a>
                        
                        <button onclick="window.print()" class="flex items-center justify-center space-x-2 px-6 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-2xl font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all shadow-md group">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span>{{ __('Print ID Card') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-gradient {
            background-size: 400% 400%;
            animation: gradient 10s ease infinite;
        }
        
        @media print {
            header, nav, aside, button, .max-w-4xl > div > div:last-child {
                display: none !important;
            }
            body { background: white !important; }
            .py-12 { padding: 0 !important; }
            .max-w-4xl { max-width: none !important; margin: 0 !important; }
            .md\:w-1\/2 { width: 100% !important; }
            .bg-gradient-to-br { box-shadow: none !important; border-radius: 0 !important; }
        }
    </style>
</x-app-layout>
