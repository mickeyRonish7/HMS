@props(['user' => auth()->user()])

@php
    $currentFontSize = $user ? $user->font_size : (session('font_size', 'medium'));
@endphp

<div x-data="{ open: false, fontSize: '{{ $currentFontSize }}' }" class="relative">
    <button 
        @click="open = !open" 
        @click.away="open = false"
        class="flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
        title="Font size"
        aria-label="Change font size"
    >
        <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
        </svg>
    </button>

    <!-- Dropdown -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
        style="display: none;"
    >
        <div class="py-1">
            <button 
                @click="changeFontSize('small')" 
                class="flex items-center w-full px-4 py-2 text-sm {{ $currentFontSize === 'small' ? 'bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
            >
                <span class="text-xs">A</span>
                <span class="ml-2">{{ __('Small') }}</span>
                @if($currentFontSize === 'small')
                    <svg class="ml-auto w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
            </button>
            <button 
                @click="changeFontSize('medium')" 
                class="flex items-center w-full px-4 py-2 text-sm {{ $currentFontSize === 'medium' ? 'bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
            >
                <span class="text-sm">A</span>
                <span class="ml-2">{{ __('Medium') }}</span>
                @if($currentFontSize === 'medium')
                    <svg class="ml-auto w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
            </button>
            <button 
                @click="changeFontSize('large')" 
                class="flex items-center w-full px-4 py-2 text-sm {{ $currentFontSize === 'large' ? 'bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
            >
                <span class="text-base">A</span>
                <span class="ml-2">{{ __('Large') }}</span>
                @if($currentFontSize === 'large')
                    <svg class="ml-auto w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                @endif
            </button>
        </div>
    </div>
</div>

<script>
function changeFontSize(size) {
    this.fontSize = size;
    this.open = false;
    
    // Update DOM
    document.documentElement.classList.remove('font-small', 'font-medium', 'font-large');
    document.documentElement.classList.add('font-' + size);
    
    // Save to server
    fetch('/theme/font-size', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ font_size: size })
    });
}
</script>
