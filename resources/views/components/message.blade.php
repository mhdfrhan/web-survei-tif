@if (session('success'))
    <div class="bg-green-200 dark:bg-green-800 overflow-hidden shadow-sm sm:rounded-lg fixed top-4 right-4 z-[999]"
        x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
        x-transition:enter="transform transition duration-300" x-transition:enter-start="scale-90 opacity-0"
        x-transition:enter-end="scale-100 opacity-100" x-transition:leave="transform transition duration-300"
        x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-95 opacity-0">
        <div class="py-3.5 flex items-center gap-2 px-7 text-green-800 dark:text-green-100">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5 text-green-800">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>

            {{ session('success') }}
        </div>
    </div>
@elseif (session('error'))
    <div class="bg-red-200 dark:bg-red-800 overflow-hidden shadow-sm sm:rounded-lg fixed top-4 right-4 z-[999]"
        x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
        x-transition:enter="transform transition duration-300" x-transition:enter-start="scale-90 opacity-0"
        x-transition:enter-end="scale-100 opacity-100" x-transition:leave="transform transition duration-300"
        x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-95 opacity-0">
        <div class="py-3.5 flex items-center gap-2 px-7 text-red-800 dark:text-red-100">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5 text-red-800">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>

            {{ session('error') }}
        </div>
    </div>
@endif
