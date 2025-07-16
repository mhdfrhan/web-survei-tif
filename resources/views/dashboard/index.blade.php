<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xs sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}!</h1>
                    <p class="mb-6 text-gray-600">Berikut adalah rekap total data survey yang telah masuk:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @php
                            $surveyTypes = [
                                'VMTS' => 'VMTS',
                                'DOSEN' => 'Dosen',
                                'TENDIK' => 'Tendik',
                                'MAHASISWA' => 'Mahasiswa'
                            ];
                            $totals = [];
                            foreach ($surveyTypes as $key => $label) {
                                $type = \App\Models\SurveyTypes::where('name', $key)->first();
                                $totals[$key] = $type ? $type->responses()->count() : 0;
                            }
                        @endphp
                        @foreach ($surveyTypes as $key => $label)
                            <div class="bg-navy-50 rounded-xl shadow p-6 flex flex-col items-center">
                                <span class="text-lg font-semibold text-navy-700 mb-2">{{ $label }}</span>
                                <span class="text-3xl font-bold text-navy-800 mb-1">{{ $totals[$key] }}</span>
                                <span class="text-xs text-gray-500">Total Survey</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>