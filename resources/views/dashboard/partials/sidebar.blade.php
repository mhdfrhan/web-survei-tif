<aside class="w-[20rem] h-svh bg-navy-600 hidden xl:block fixed top-0 left-0 bottom-0 z-50">
    <!-- Logo -->
    <div class="shrink-0 flex items-center p-5 pb-0 w-full">
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button
                    class="flex items-center justify-between w-full border p-3 border-navy-500 rounded-lg cursor-pointer">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets/img/logo.png') }}" class="h-12"
                            alt="{{ config('app.name', 'Laravel') }} Logo">
                        <div>
                            <h5 class="font-semibold line-clamp-2 capitalize leading-none text-left text-white">Halo,
                                {{ auth()->user()->name }}
                            </h5>
                            <p class="text-sm text-navy-200">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div>
                        <svg class="size-5 text-neutral-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
                            <rect width="256" height="256" fill="none" />
                            <polyline points="80 176 128 224 176 176" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                            <polyline points="80 80 128 32 176 80" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="16" />
                        </svg>
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-dropdown-link>

                <!-- Authentication -->
                {{-- <livewire:logout> --}}
            </x-slot>
        </x-dropdown>
    </div>

    <div class="h-[calc(100vh-5rem)] overflow-y-auto">
        <ul class="flex flex-col p-5 pt-2 space-y-1.5">

            <li>
                <x-nav-link class="inline-flex gap-3 text-sm" :href="route('home')">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    {{ __('Kembali ke beranda') }}
                </x-nav-link>
            </li>

            <div class="text-neutral-400 pt-4 uppercase text-sm font-medium">
                <p>Main menu</p>
            </div>

            <li>
                <x-nav-link class="inline-flex gap-3" :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                    </svg>

                    {{ __('Dashboard') }}
                </x-nav-link>
            </li>

            <li>
                <x-nav-dropdown title="Survey" routePattern="dashboard/survei*" :active="request()->is('dashboard/survey*')"
                    icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="shrink-0 size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                    '>

                    <ul>
                        <li class="px-1 py-0.5 first:mt-2">
                            <a href="{{ route('dashboard.survey.vmts') }}" wire:navigate
                                class="flex items-center rounded-lg gap-2 px-4 py-1.5 focus:outline-none font-medium border duration-150 {{ request()->routeIs('dashboard.survey.vmts') ? 'border-navy-500 text-white' : 'border-transparent hover:border-navy-500 text-navy-200 hover:text-navy-50' }}">
                                VMTS
                            </a>
                        </li>
                        <li class="px-1 py-0.5 first:mt-2">
                            <a href="{{ route('dashboard.survey.dosen') }}" wire:navigate
                                class="flex items-center rounded-lg gap-2 px-4 py-1.5 focus:outline-none font-medium border duration-150 {{ request()->routeIs('dashboard.survey.dosen') ? 'border-navy-500 text-white' : 'border-transparent hover:border-navy-500 text-navy-200 hover:text-navy-50' }}">
                                Dosen
                            </a>
                        </li>
                        <li class="px-1 py-0.5 first:mt-2">
                            <a href="{{ route('dashboard.survey.tendik') }}" wire:navigate
                                class="flex items-center rounded-lg gap-2 px-4 py-1.5 focus:outline-none font-medium border duration-150 {{ request()->routeIs('dashboard.survey.tendik') ? 'border-navy-500 text-white' : 'border-transparent hover:border-navy-500 text-navy-200 hover:text-navy-50' }}">
                                Tendik
                            </a>
                        </li>
                        <li class="px-1 py-0.5 first:mt-2">
                            <a href="{{ route('dashboard.survey.mahasiswa') }}" wire:navigate
                                class="flex items-center rounded-lg gap-2 px-4 py-1.5 focus:outline-none font-medium border duration-150 {{ request()->routeIs('dashboard.survey.mahasiswa') ? 'border-navy-500 text-white' : 'border-transparent hover:border-navy-500 text-navy-200 hover:text-navy-50' }}">
                                Mahasiswa
                            </a>
                        </li>
                    </ul>
                </x-nav-dropdown>

            <li>
                <x-nav-link class="inline-flex gap-3" :href="route('dashboard.rekap.data')" :active="request()->routeIs('dashboard.rekap.data')" wire:navigate>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>

                    {{ __('Rekap Data Survey') }}
                </x-nav-link>
            </li>

            </li>
        </ul>
    </div>
</aside>
