<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.feedback.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Feedback Analytics') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium uppercase">{{ __('Total Feedback') }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalFeedbacks }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 text-yellow-500">
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium uppercase">{{ __('Avg. Room Rating') }}</p>
                    <div class="flex items-end space-x-2">
                        <p class="text-3xl font-bold mt-1">{{ number_format($averageRatings['room'], 1) }}</p>
                        <span class="mb-1 text-lg">/ 5</span>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 text-blue-500">
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium uppercase">{{ __('Avg. Mess Rating') }}</p>
                    <div class="flex items-end space-x-2">
                        <p class="text-3xl font-bold mt-1">{{ number_format($averageRatings['mess'], 1) }}</p>
                        <span class="mb-1 text-lg">/ 5</span>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 text-green-500">
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium uppercase">{{ __('Avg. Security Rating') }}</p>
                    <div class="flex items-end space-x-2">
                        <p class="text-3xl font-bold mt-1">{{ number_format($averageRatings['security'], 1) }}</p>
                        <span class="mb-1 text-lg">/ 5</span>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Rating Distribution -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('General Satisfaction') }}</h3>
                    <div class="h-64">
                        <canvas id="satisfactionChart"></canvas>
                    </div>
                </div>

                <!-- Monthly Trends -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">{{ __('Monthly Feedback Volume') }}</h3>
                    <div class="h-64">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // General Satisfaction Donut Chart
            const satisfactionCtx = document.getElementById('satisfactionChart').getContext('2d');
            new Chart(satisfactionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Excellent (4-5)', 'Good (3-4)', 'Average (2-3)', 'Poor (0-2)'],
                    datasets: [{
                        data: [
                            {{ $satisfactionDistribution['Excellent'] }},
                            {{ $satisfactionDistribution['Good'] }},
                            {{ $satisfactionDistribution['Average'] }},
                            {{ $satisfactionDistribution['Poor'] }},
                        ],
                        backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // Monthly Trends Bar Chart
            @php
                // Process trends for chart keys/values 
                // $trends is already a collection from controller
            @endphp
            
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($trends->pluck('month')) !!},
                    datasets: [{
                        label: 'Feedback Count',
                        data: {!! json_encode($trends->pluck('count')) !!},
                        backgroundColor: '#6366F1',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
