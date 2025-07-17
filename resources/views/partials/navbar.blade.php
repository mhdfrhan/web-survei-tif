<nav x-data="{
    open: false,
    aboutDropdown: false,
    layananDropdown: false,
    scrolled: false,
    init() {
        window.addEventListener('scroll', () => {
            this.scrolled = window.pageYOffset > 20
        })
    }
}" :class="{
    'sticky top-4 px-8': scrolled,
    'sticky top-0': !scrolled
}"
    class="w-full z-50 transition-all duration-500">
    <div :class="{
        'lg:w-7xl mx-auto rounded-2xl bg-white/90 backdrop-blur-sm border border-neutral-300': scrolled,
        'rounded-none mx-auto bg-white w-full border-b border-neutral-200': !scrolled
    }"
        class=" transition-all duration-300">
        <div class=" max-w-7xl mx-auto px-4 lg:px-8">
            <div class="flex justify-between h-20">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2" wire:navigate>
                        <img class="h-14 w-auto" src="{{ asset('assets/img/logo.png') }}" alt="Logo">
                        <img class="h-14 w-auto" src="{{ asset('assets/img/logo-fasilkom.png') }}" alt="Logo">
                    </a>
                </div>

                <div class="hidden lg:flex items-center space-x-0.5">
                    <a href="{{ route('home') }}" wire:navigate
                        class=" px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 ease-in-out hover:bg-navy-50 {{ request()->routeIs('home') ? 'font-semibold text-navy-500' : 'text-neutral-700 hover:text-navy-800' }}">
                        Beranda
                    </a>

                    <a href="https://fasilkom.umri.ac.id/" target="_blank"
                        class=" px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 ease-in-out hover:bg-navy-50 hover:text-navy-800">
                        Fakultas Ilmu Komputer
                    </a>

                </div>

                @auth
                    <div class="flex items-center space-x-2">
                        <div class="hidden lg:flex items-center ml-4">
                            <a href="{{ route('dashboard') }}">
                                <x-primary-button>Dashboard</x-primary-button>
                            </a>
                        </div>
                    </div>
                @endauth

                <div class="flex items-center space-x-2 lg:hidden">
                        <div class="flex items-center lg:hidden">
                            <button @click="open = !open"
                                class="inline-flex items-center justify-center p-2 rounded-full text-neutral-600 hover:text-navy-500 hover:bg-navy-100 focus:outline-none transition-all duration-300">
                                <svg class="h-6 w-6" x-show="!open" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <svg class="h-6 w-6" x-show="open" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
            </div>
        </div>

        <!-- Mobile menu -->
         <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            class="lg:hidden bg-white/95 backdrop-blur overflow-y-auto max-h-screen overflow-hidden">
            <div class="px-4 pt-2 pb-3 space-y-1" x-cloak>
                <a href="{{ route('home') }}" wire:navigate
                    class="block px-4 py-3 rounded-lg text-base font-medium text-neutral-700 hover:text-navy-500 hover:bg-navy-100 transition-all duration-300">Beranda</a>

                <a href="https://informatika.fasilkom.umri.ac.id/" target="_blank"
                    class="block px-4 py-3 rounded-lg text-base font-medium text-neutral-700 hover:text-navy-500 hover:bg-navy-100 transition-all duration-300">Teknik Informatika</a>
            </div>
        </div>
    </div>
</nav>
