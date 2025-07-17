<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Survei Teknik Informatika Universitas Negeri Muhammadiyah Riau">

    <title>{{ $title }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>

    <header x-data="{ mainNavOpen: false }">
        <!-- Baris Atas - Navbar Oranye -->
        <div class="bg-[#e27a39] text-white text-sm font-semibold">
            <div class="max-w-5xl mx-auto flex flex-wrap items-center justify-between px-4 py-2">
                <div class="flex space-x-4 overflow-x-auto whitespace-nowrap text-xs">
                    <a href="#" class="hover:underline">Tentang UMRI</a>
                    <a href="#" class="hover:underline">PMB</a>
                    <a href="#" class="hover:underline">DAAK</a>
                    <a href="#" class="hover:underline">DAUK</a>
                    <a href="#" class="hover:underline">Perpustakaan</a>
                    <a href="#" class="hover:underline">MBKM</a>
                    <a href="#" class="hover:underline">Jurnal CoSciTech</a>
                    <a href="#" class="hover:underline">KATIF</a>
                    <a href="#" class="hover:underline">Sertifikat</a>
                </div>
                <div class="relative mt-2 md:mt-0">
                    <form action="/post" method="get" class="flex">
                        <input type="search" name="keyword" placeholder="Enter keywords"
                            class="w-52 md:w-64 px-2 py-1.5 text-neutral-400 text-xs focus:outline-none">
                        <button type="submit" class="bg-navy-header px-3 rounded-r text-white hover:bg-blue-900">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="3" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Baris Bawah - Navbar Biru dengan Background -->
        <div class="relative bg-cover bg-center"
            style="background-image: url('https://informatika.fasilkom.umri.ac.id/media/2024-05-29-171695659597-header');">
            <!-- Overlay biru transparan -->
            <div class="bg-navy-header/80">
                <div class="max-w-5xl mx-auto flex flex-wrap items-center justify-between px-4 py-3">
                    <!-- Logo & Judul -->
                    <div class="flex items-center space-x-4">
                        <img src="https://informatika.fasilkom.umri.ac.id/media/2024-01-23-170598301085-umri"
                            alt="Logo" class="h-14 md:h-16" />
                        <div>
                            <h1 class="text-lg font-bold text-white">Teknik Informatika</h1>
                            <p class="text-sm text-white">Fakultas Ilmu Komputer<br>Universitas
                                Muhammadiyah Riau</p>
                        </div>
                    </div>

                    <!-- Main Navigation -->
                    <div class="mt-4 md:mt-0">
                        <ul class="hidden md:flex space-x-6 font-semibold text-xs items-center text-white">
                            <li><a href="https://informatika.fasilkom.umri.ac.id/" class="hover:underline">HOME</a></li>
                            <li class="relative group">
                                <a href="#" class="hover:underline">PROFIL</a>
                                <ul
                                    class="absolute hidden group-hover:block bg-white text-black mt-1 rounded shadow-lg z-10">
                                    <li><a href="https://informatika.fasilkom.umri.ac.id/page/visi-misi-dan-tujuan" class="block px-4 py-2 hover:bg-gray-100">Visi Misi</a></li>
                                    <li><a href="https://informatika.fasilkom.umri.ac.id/page/dosen" class="block px-4 py-2 hover:bg-gray-100">Dosen</a></li>
                                    <li><a href="https://informatika.fasilkom.umri.ac.id/page/profil-dan-profesi-lulusan" class="block px-4 py-2 hover:bg-gray-100">Profil Lulusan</a></li>
                                    <li><a href="https://informatika.fasilkom.umri.ac.id/page/kurikulum" class="block px-4 py-2 hover:bg-gray-100">Kurikulum</a></li>
                                </ul>
                            </li>
                            <li><a href="#" class="hover:underline">FASILITAS</a></li>
                            <li class="relative group">
                                <a href="#" class="hover:underline">LAYANAN</a>
                                <ul
                                    class="absolute hidden group-hover:block bg-white text-black mt-1 rounded shadow-lg z-10">
                                    <li><a href="https://informatika.fasilkom.umri.ac.id/page/kerja-praktik" class="block px-4 py-2 hover:bg-gray-100">Kerja Praktik</a></li>
                                    <li><a href="https://informatika.fasilkom.umri.ac.id/page/jadwal-skripsi" class="block px-4 py-2 hover:bg-gray-100">Skripsi</a></li>
                                    <li><a href="https://informatika.fasilkom.umri.ac.id/page/form-pendaftaran-kp-dan-skripsi" class="block px-4 py-2 hover:bg-gray-100">Pendaftaran KP dan Skripsi</a></li>
                                </ul>
                            </li>
                            <li><a href="https://informatika.fasilkom.umri.ac.id/post" class="hover:underline">BERITA</a></li>
                            <li><a href="https://informatika.fasilkom.umri.ac.id/page/pusat-download" class="hover:underline">PUSAT DOWNLOAD</a></li>
                            <li class="relative group">
                                <a href="#" class="hover:underline">KEMAHASISWAAN</a>
                                <ul
                                    class="absolute hidden group-hover:block bg-white text-black mt-1 rounded shadow-lg z-10">
                                    <li><a href="https://informatika.fasilkom.umri.ac.id/page/luaran-pembelajaran" class="block px-4 py-2 hover:bg-gray-100">Luaran Pembelajaran</a></li>
                                    <li><a href="https://informatika.fasilkom.umri.ac.id/page/mbkm" class="block px-4 py-2 hover:bg-gray-100">MBKM</a></li>
                                    <li><a href="https://informatika.fasilkom.umri.ac.id/page/prestasi-mahasiswa" class="block px-4 py-2 hover:bg-gray-100">Prestasi Mahasiswa</a></li>
                                    <li><a href="https://informatika.fasilkom.umri.ac.id/page/kkn-internasional" class="block px-4 py-2 hover:bg-gray-100">KKN Internasional</a></li>
                                    <li><a href="https://informatika.fasilkom.umri.ac.id/page/kegiatan-himpunan" class="block px-4 py-2 hover:bg-gray-100">Kegiatan Himpunan</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <div x-show="mainNavOpen" class="md:hidden bg-blue-900/90 px-4 pb-4 space-y-2 text-white">
                    <a href="https://informatika.fasilkom.umri.ac.id/" class="block">HOME</a>
                    <a href="https://informatika.fasilkom.umri.ac.id/page/visi-misi-dan-tujuan" class="block">PROFIL</a>
                    <a href="#" class="block">FASILITAS</a>
                    <a href="https://informatika.fasilkom.umri.ac.id/page/kerja-praktik" class="block">LAYANAN</a>
                    <a href="https://informatika.fasilkom.umri.ac.id/post" class="block">BERITA</a>
                    <a href="https://informatika.fasilkom.umri.ac.id/page/pusat-download" class="block">PUSAT DOWNLOAD</a>
                    <a href="https://informatika.fasilkom.umri.ac.id/page/luaran-pembelajaran" class="block">KEMAHASISWAAN</a>
                </div>
            </div>
        </div>

    </header>


    <main class="pt-14 min-h-screen">
        <x-container>
            {{ $slot }}
        </x-container>
    </main>

    @include('partials.footer')

    @livewireScripts
</body>

</html>
