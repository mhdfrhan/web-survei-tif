@props(['title', 'icon', 'active' => false, 'routePattern', 'contentClasses' => 'mt-2 space-y-1'])

<li>
    <div 
        x-data="{ isExpanded: {{ request()->is($routePattern) ? 'true' : 'false' }} }" 
        class="flex flex-col relative group {{ $active ? 'pl-4' : 'hover:pl-4' }} duration-300">

        {{-- Garis indikator kiri --}}
        @if ($active)
            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-cgreen-500 rounded"></span>
        @else
            <span class="opacity-0 invisible group-hover:opacity-100 group-hover:visible absolute left-0 top-1/2 -translate-y-1/2 w-1 h-0 group-hover:h-6 bg-cgreen-500 rounded duration-300"></span>
        @endif

        {{-- Button utama --}}
        <button 
            type="button" 
            x-on:click="isExpanded = ! isExpanded" 
            aria-controls="{{ Str::slug($title) }}" 
            x-bind:aria-expanded="isExpanded ? 'true' : 'false'"
            class="inline-flex items-center gap-3 py-2.5 px-3 rounded-xl font-medium w-full border transition duration-150 ease-in-out relative
                {{ $active ? 'text-navy-800 bg-white border-navy-500' : 'text-navy-200 hover:text-navy-800 hover:bg-white hover:border-navy-500 border-transparent' }}">

            {!! $icon !!}

            <span class="mr-auto text-left">{{ $title }}</span>

            {{-- Icon panah --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"
                class="size-5 transition-transform shrink-0" 
                x-bind:class="isExpanded ? 'rotate-180' : 'rotate-0'">
                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </button>

        {{-- Dropdown isi --}}
        <div x-cloak x-collapse x-show="isExpanded" aria-labelledby="{{ Str::slug($title) }}-btn" id="{{ Str::slug($title) }}">
            <ul class="{{ $contentClasses }}">
                {{ $slot }}
            </ul>
        </div>
    </div>
</li>
