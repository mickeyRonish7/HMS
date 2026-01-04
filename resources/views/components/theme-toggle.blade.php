@props(['user' => auth()->user()])

@php
    $currentTheme = $user ? $user->theme : (session('theme', 'light'));
@endphp

<div x-data="{ theme: '{{ $currentTheme }}' }" class="relative">
    <button 
        @click="toggleTheme()" 
        class="flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200"
        title="Toggle theme"
        aria-label="Toggle dark mode"
    >
        <!-- Sun Icon (Light Mode) -->
        <svg x-show="theme === 'dark'" class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
        </svg>
        
        <!-- Moon Icon (Dark Mode) -->
        <svg x-show="theme === 'light'" class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
        </svg>
    </button>
</div>

<script>
function toggleTheme() {
    const newTheme = this.theme === 'light' ? 'dark' : 'light';
    this.theme = newTheme;
    
    // Update DOM
    if (newTheme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
    
    // Save to server
    fetch('/theme/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ theme: newTheme })
    });
}
</script>
