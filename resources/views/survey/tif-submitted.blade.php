<x-tif-layout>
    <x-slot name="title">Survey Berhasil Dikirim</x-slot>

    <div class="max-w-xl mx-auto py-12 bg-white shadow-2xl shadow-neutral-200 rounded-2xl">
        <div class="flex flex-col items-center">
            <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.6.2/dist/dotlottie-wc.js" type="module"></script>
            <dotlottie-wc src="https://lottie.host/5fc63b75-1920-4a5f-a8e5-1362f4504b25/UYPiWQ5YOO.lottie"
                class="mx-auto mb-6" style="width: 220px; height: 220px" speed="1" autoplay loop></dotlottie-wc>
            <h1 class="text-2xl font-bold text-navy-700 mb-2">Terima Kasih!</h1>
            <p class="text-gray-600 text-center mb-6">
                Jawaban survey Anda telah berhasil dikirim.<br>
                Kami sangat menghargai partisipasi Anda.
            </p>
            <a href="https://informatika.fasilkom.umri.ac.id/">
                <x-primary-button>
						Kembali ke Beranda
					 </x-primary-button>
            </a>
        </div>
    </div>
</x-tif-layout>