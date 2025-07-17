<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-navy-800">Data Survey</h1>
                <p class="text-navy-600 mt-2">
                    Kelola data survey pengukuran pemahaman Fakultas Ilmu Komputer
                </p>
            </div>
            <div class="flex space-x-3">
                <x-primary-button wire:click="exportExcel" class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                    </svg>

                    Export Excel
                </x-primary-button>
            </div>
        </div>
    </div>
    <div class="flex flex-col md:flex-row md:items-end md:justify-end gap-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Jenis Survey</label>
                <select wire:model.live="surveyType"
                    class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-navy-500">
                    @foreach ($surveyTypes as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @if ($surveyType === 'vmts' || $surveyType === 'vmtstif')
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Kategori Responden</label>
                    <select wire:model.live="respondentCategory"
                        class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-navy-500">
                        <option value="">Semua</option>
                        @foreach ($respondentCategories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>

    <div class="mb-4 flex items-center justify-between">
        <div>
            <span class="text-base font-semibold text-navy-700">
                Total Data Survey: {{ $responses->total() }}
            </span>
        </div>
    </div>

    @foreach ($sections as $section)
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-navy-800 mb-4">{{ $section->section_title }}</h2>
            <div class="overflow-x-auto">
                <div class="min-w-max">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-navy-50">
                            <tr>
                                <th class="px-4 py-3 uppercase text-left text-xs font-semibold text-navy-700">#</th>
                                <th class="px-4 py-3 uppercase text-left text-xs font-semibold text-navy-700">Tanggal
                                </th>
                                @foreach ($section->questions as $q)
                                    <th class="px-4 py-3 uppercase text-center text-xs font-semibold text-navy-700 max-w-20" title="{{ $q->question_text }}">
                                        <p class="line-clamp-1">{{ $q->question_text }}
                                    </th>
                                    </p>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($responses as $i => $row)
                                <tr class="hover:bg-navy-50/50">
                                    <td class="px-4 py-2 text-sm text-neutral-700">{{ $responses->firstItem() + $i }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-neutral-700">
                                        {{ date('d F Y', strtotime($row->submitted_at)) }}</td>
                                    @foreach ($section->questions as $q)
                                        @php
                                            $answers = json_decode($row->question_answers, true) ?? [];
                                            $answer = $answers[$q->id] ?? '-';
                                        @endphp
                                        <td class="px-4 py-2 text-sm text-neutral-700 text-center">
                                            <span class="text-navy-800 bg-navy-100 px-2 py-1 rounded-lg inline-block">
                                                @if (is_array($answer))
                                                    {{ implode(', ', $answer) }}
                                                @else
                                                    {{ $answer }}
                                                @endif
                                            </span>
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($section->questions) + 2 }}"
                                        class="px-4 py-8 text-center text-neutral-400">Tidak ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    <x-modal name="view-response-modal" :show="$showViewResponseModal" focusable align="center" maxWidth="3xl">
        <div class="p-8">
            <h2 class="text-xl font-bold text-navy-800 mb-4">Detail Response Survey</h2>
            @if ($selectedResponse)
                <div class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <span class="block text-sm font-semibold text-navy-700 mb-2">Tanggal Submit</span>
                            <span
                                class="block text-neutral-700">{{ date('d F Y', strtotime($selectedResponse->submitted_at)) }}</span>
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-navy-700 mb-2">Kategori Responden</span>
                            <span
                                class="block text-neutral-700 capitalize">{{ $selectedResponse->respondent_category ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-sm font-semibold text-navy-700 mb-2">IP Address</span>
                            <span class="block text-neutral-700">{{ $selectedResponse->ip_address }}</span>
                        </div>
                    </div>
                </div>
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-navy-700 mb-3">Data Form Fields</h3>
                    <div class="space-y-2">
                        @foreach ($formFields as $field)
                            @php
                                $value = json_decode($selectedResponse->form_data, true)[$field['field_name']] ?? '-';
                            @endphp
                            <div class="flex justify-between items-center">
                                <span class="text-neutral-700 font-medium">{{ $field['field_label'] }}</span>
                                <span
                                    class="text-navy-800">{{ is_array($value) ? json_encode($value) : $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-navy-700 mb-3">Jawaban Pertanyaan Survey</h3>
                    <div class="space-y-4">
                        @foreach ($questions as $q)
                            @php
                                $answers = json_decode($selectedResponse->question_answers, true);
                                $answer = $answers[$q['id']] ?? '-';
                            @endphp
                            <div>
                                <span class="text-sm font-semibold text-navy-700 mb-1">{{ $loop->iteration }}. </span>
                                <span class=" text-neutral-700 font-medium mb-1">{{ $q['question_text'] }} : </span>
                                <span class=" text-navy-800 bg-navy-100 px-2 py-1 rounded-lg">
                                    @if (is_array($answer))
                                        {{ implode(', ', $answer) }}
                                    @else
                                        {{ $answer }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center text-neutral-500 py-12">Data response tidak ditemukan.</div>
            @endif
            <div class="pt-8 flex justify-end">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">
                    Tutup
                </x-secondary-button>
            </div>
        </div>
    </x-modal>

    @if ($hasMorePages)
        <div x-data="{
            observe() {
                let observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            $wire.loadMore();
                        }
                    });
                });
                observer.observe(this.$el);
            }
        }" x-init="observe" class="w-full flex justify-center py-6">
            <span class="text-navy-600 animate-pulse">Memuat data...</span>
        </div>
    @endif
</div>
