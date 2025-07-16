<div x-data="{ show: false, message: '', type: 'success' }" x-show="show"
    x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type || 'success'; setTimeout(() => { show = false }, 3000)"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90"
    class="fixed top-4 right-4 z-[9999] p-4 rounded-xl"
    :class="{
        'bg-green-500 text-green-100': type === 'success',
        'bg-red-500 text-red-100': type === 'error',
        'bg-orange-500 text-orange-100': type === 'warning',
        'bg-blue-500 text-blue-100': type === 'info'
    }">
    <div class="flex items-center">
        <svg x-show="type === 'success'" class="size-5 mr-3 text-white fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
            <rect width="256" height="256" fill="none" />
            <polyline points="88 136 112 160 168 104" fill="none" stroke="currentColor" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="16" />
            <circle cx="128" cy="128" r="96" fill="none" stroke="currentColor" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="16" />
        </svg>

        <svg x-show="type === 'error'" class="size-5 mr-3 text-white fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
            <rect width="256" height="256" fill="none" />
            <circle cx="128" cy="128" r="96" fill="none" stroke="currentColor" stroke-miterlimit="10"
                stroke-width="16" />
            <line x1="128" y1="136" x2="128" y2="80" fill="none" stroke="currentColor"
                stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
            <circle cx="128" cy="172" r="12" />
        </svg>

        <svg x-show="type === 'warning'" class="size-5 mr-3 text-white fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
            <rect width="256" height="256" fill="none" />
            <path
                d="M142.41,40.22l87.46,151.87C236,202.79,228.08,216,215.46,216H40.54C27.92,216,20,202.79,26.13,192.09L113.59,40.22C119.89,29.26,136.11,29.26,142.41,40.22Z"
                fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
            <line x1="128" y1="144" x2="128" y2="104" fill="none" stroke="currentColor"
                stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
            <circle cx="128" cy="180" r="12" />
        </svg>

        <svg x-show="type === 'info'" class="size-5 mr-3 text-white fill-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
            <rect width="256" height="256" fill="none" />
            <circle cx="128" cy="128" r="96" fill="none" stroke="currentColor" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="16" />
            <path d="M120,120a8,8,0,0,1,8,8v40a8,8,0,0,0,8,8" fill="none" stroke="currentColor"
                stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
            <circle cx="124" cy="84" r="12" />
        </svg>

        <span x-text="message"></span>
    </div>
</div>
