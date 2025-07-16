<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-navy-600">
        <div class="min-h-screen">
            <livewire:layout.navigation />
            @include('dashboard.partials.sidebar')

            <!-- Page Content -->
            <main class="bg-white min-h-screen xl:ml-[20rem] rounded-3xl lg:m-2 border border-neutral-300">
                {{ $slot }}
            </main>
        </div>


        @stack('scripts')
    </body>
</html>
