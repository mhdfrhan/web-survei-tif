<x-main-layout>
    <x-slot name="title">Home</x-slot>

    <div class="min-h-[70vh] flex flex-col items-center justify-center bg-gradient-to-br from-navy-100 via-navy-50 to-blue-50 py-12 px-4 rounded-2xl">
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-5xl font-extrabold text-navy-800 mb-4">
                Selamat Datang di Survey Teknik Informatika!
            </h1>
            <p class="text-lg md:text-xl text-navy-700 font-medium max-w-2xl mx-auto">
                Silakan pilih jenis survey yang ingin Anda isi. Setiap suara Anda sangat berarti untuk kemajuan institusi.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-2xl">
            <a href="{{ route('survey.vmts') }}" wire:navigate
                class="group relative flex flex-col items-center justify-center p-8 rounded-2xl shadow-xl bg-gradient-to-tr from-navy-600 to-navy-500 hover:from-navy-700 hover:to-navy-600 hover:scale-105 transition-all duration-300 hover:shadow-2xl cursor-pointer">
                <span class="text-3xl mb-2 group-hover:scale-110 transition">🎯</span>
                <span class="text-xl font-bold text-white mb-1">Survey VMTS</span>
                <span class="text-navy-100 text-sm opacity-90">Visi, Misi, Tujuan, dan Sasaran</span>
                <span class="absolute top-2 right-4 text-white/20 text-5xl font-black pointer-events-none select-none">1</span>
            </a>
            <a href="{{ route('survey.dosen') }}" wire:navigate
                class="group relative flex flex-col items-center justify-center p-8 rounded-2xl shadow-xl bg-gradient-to-tr from-navy-700 to-navy-600 hover:from-navy-800 hover:to-navy-700 hover:scale-105 transition-all duration-300 hover:shadow-2xl cursor-pointer">
                <span class="text-3xl mb-2 group-hover:scale-110 transition">👨‍🏫</span>
                <span class="text-xl font-bold text-white mb-1">Survey Dosen</span>
                <span class="text-navy-100 text-sm opacity-90">Kepuasan Dosen</span>
                <span class="absolute top-2 right-4 text-white/20 text-5xl font-black pointer-events-none select-none">2</span>
            </a>
            <a href="{{ route('survey.tendik') }}" wire:navigate
                class="group relative flex flex-col items-center justify-center p-8 rounded-2xl shadow-xl bg-gradient-to-tr from-navy-800 to-navy-700 hover:from-navy-900 hover:to-navy-800 hover:scale-105 transition-all duration-300 hover:shadow-2xl cursor-pointer">
                <span class="text-3xl mb-2 group-hover:scale-110 transition">🧑‍💼</span>
                <span class="text-xl font-bold text-white mb-1">Survey Tendik</span>
                <span class="text-navy-100 text-sm opacity-90">Kepuasan Tenaga Kependidikan</span>
                <span class="absolute top-2 right-4 text-white/20 text-5xl font-black pointer-events-none select-none">3</span>
            </a>
            <a href="{{ route('survey.mahasiswa') }}" wire:navigate
                class="group relative flex flex-col items-center justify-center p-8 rounded-2xl shadow-xl bg-gradient-to-tr from-navy-500 to-navy-400 hover:from-navy-600 hover:to-navy-500 hover:scale-105 transition-all duration-300 hover:shadow-2xl cursor-pointer">
                <span class="text-3xl mb-2 group-hover:scale-110 transition">🎓</span>
                <span class="text-xl font-bold text-white mb-1">Survey Mahasiswa</span>
                <span class="text-navy-100 text-sm opacity-90">Kepuasan Mahasiswa</span>
                <span class="absolute top-2 right-4 text-white/20 text-5xl font-black pointer-events-none select-none">4</span>
            </a>
        </div>
    </div>
</x-main-layout>