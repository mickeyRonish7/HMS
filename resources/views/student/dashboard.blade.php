<x-app-layout>
    <x-slot name="header">
        {{ __('Student Dashboard') }}
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Room Status Card with Photo -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border-l-4 border-blue-500">
            @if(Auth::user()->bed && Auth::user()->bed->room->room_photo)
                <div class="h-32 bg-gray-200 dark:bg-gray-700">
                    <img src="{{ asset('storage/' . Auth::user()->bed->room->room_photo) }}" alt="My Room" class="w-full h-full object-cover">
                </div>
            @endif
            <div class="p-4">
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('My Room') }}</p>
                <p class="text-xl font-bold text-gray-800 dark:text-white mt-1">
                    {{ Auth::user()->bed ? __('Room').' ' . Auth::user()->bed->room->room_number : __('Not Assigned') }}
                </p>
                @if(Auth::user()->bed)
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-bold mt-1">{{ __('Bed') }} {{ Auth::user()->bed->bed_number }}</p>
                @endif
            </div>
        </div>

        <!-- Academic Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 border-purple-500 flex items-center justify-between">
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('Academic Info') }}</p>
                <p class="text-lg font-bold text-gray-800 dark:text-white mt-1">{{ Auth::user()->year }} | {{ Auth::user()->semester }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ Auth::user()->department }}</p>
            </div>
            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-full text-purple-600 dark:text-purple-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
        </div>

        <!-- Due Fees Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 border-red-500 flex items-center justify-between">
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('Pending Fees') }}</p>
                @php
                    $pendingFees = Auth::user()->fees()->where('status', 'pending')->sum('amount');
                @endphp
                <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">Rs. {{ number_format($pendingFees) }}</p>
            </div>
            <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full text-red-600 dark:text-red-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Attendance Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 border-green-500 flex items-center justify-between">
            <div>
                 <p class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('Attendance Status') }}</p>
                 @php
                     $presentDays = \App\Models\Attendance::where('student_id', Auth::id())
                        ->whereMonth('date', now()->month)
                        ->where('status', 'present')
                        ->count();
                 @endphp
                <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $presentDays }} {{ __('Days') }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('This Month') }}</p>
            </div>
            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full text-green-600 dark:text-green-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Chart & Notices -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Attendance History -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6 flex items-center justify-between">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ __('Recent Attendance') }}
                    </span>
                    <a href="{{ route('student.attendance') }}" class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:underline">View All</a>
                </h3>
                 @php
                    $recentAttendance = \App\Models\Attendance::where('student_id', Auth::id())
                                        ->latest('date')
                                        ->take(5)
                                        ->get();
                @endphp
                @if($recentAttendance->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Date') }}</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('In') }}</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Out') }}</th>
                                    <th class="px-4 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($recentAttendance as $record)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600 font-medium">{{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('h:i A') : '-' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600 font-medium">{{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('h:i A') : '-' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 py-0.5 inline-flex text-[10px] font-black uppercase tracking-widest leading-5 rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                                {{ ucfirst($record->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-gray-400 border-2 border-dashed border-gray-100 dark:border-gray-700 rounded-xl">
                        {{ __('No attendance records found.') }}
                    </div>
                @endif
            </div>

            <!-- Recent Notices -->
             <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    {{ __('Recent Notices') }}
                </h3>
                 @php
                    $notices = \App\Models\Notice::latest()->take(3)->get();
                @endphp
                <div class="space-y-6">
                    @forelse($notices as $notice)
                        <div class="group border-l-4 border-indigo-500 pl-4 py-1">
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $notice->title }}</h4>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $notice->created_at->format('M d') }}</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2 leading-relaxed">{{ $notice->content }}</p>
                        </div>
                    @empty
                        <p class="text-center py-4 text-gray-500">{{ __('No notices posted yet.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Actions & Profile -->
        <div class="space-y-8">
             <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6 uppercase tracking-widest text-[10px] text-gray-400">{{ __('Quick Actions') }}</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('student.id-card') }}" class="flex flex-col items-center justify-center p-4 bg-indigo-50 dark:bg-indigo-900/20 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-2xl transition group">
                        <div class="bg-white dark:bg-gray-800 p-2 rounded-xl mb-2 group-hover:scale-110 transition shadow-sm">
                             <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('ID Card') }}</span>
                    </a>

                    <a href="{{ route('student.feedback.create') }}" class="flex flex-col items-center justify-center p-4 bg-pink-50 dark:bg-pink-900/20 hover:bg-pink-100 dark:hover:bg-pink-900/40 rounded-2xl transition group">
                         <div class="bg-white dark:bg-gray-800 p-2 rounded-xl mb-2 group-hover:scale-110 transition shadow-sm">
                             <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('Feedback') }}</span>
                    </a>

                    <a href="{{ route('student.fees') }}" class="flex flex-col items-center justify-center p-4 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-2xl transition group">
                        <div class="bg-white dark:bg-gray-800 p-2 rounded-xl mb-2 group-hover:scale-110 transition shadow-sm">
                             <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('Payment') }}</span>
                    </a>

                    <a href="{{ route('student.complaints.create') }}" class="flex flex-col items-center justify-center p-4 bg-yellow-50 dark:bg-yellow-900/20 hover:bg-yellow-100 dark:hover:bg-yellow-900/40 rounded-2xl transition group">
                         <div class="bg-white dark:bg-gray-800 p-2 rounded-xl mb-2 group-hover:scale-110 transition shadow-sm">
                             <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('Complaint') }}</span>
                    </a>
                </div>
            </div>

            <!-- Profile Widget -->
             <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-700 rounded-2xl shadow-xl p-8 text-white text-center relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all"></div>
                
                <div class="relative">
                    @if(Auth::user()->profile_photo_path)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="w-24 h-24 rounded-2xl mx-auto object-cover border-4 border-white/20 shadow-lg mb-4">
                    @else
                        <div class="w-24 h-24 bg-white/20 rounded-2xl mx-auto flex items-center justify-center mb-4 text-3xl font-black backdrop-blur-sm border-4 border-white/10">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <h3 class="text-xl font-black uppercase tracking-tight">{{ Auth::user()->name }}</h3>
                    <p class="opacity-70 text-xs font-bold mb-6 tracking-widest">{{ Auth::user()->email }}</p>
                    <a href="{{ route('student.profile') }}" class="inline-block px-8 py-3 bg-white text-indigo-700 hover:bg-indigo-50 rounded-xl text-xs font-black uppercase tracking-widest transition shadow-lg">{{ __('Manage Profile') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

