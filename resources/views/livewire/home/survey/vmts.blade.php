<div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    @include('components.alert')
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-navy-800 mb-2">{{ $surveyTitle ?? 'Survey VMTS' }}</h1>
            @if (!empty($surveyDescription))
                <p class="text-neutral-500 text-justify text-pretty text-sm">{{ $surveyDescription }}</p>
            @endif
        </div>
        <form wire:submit.prevent="submit" class="space-y-8">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Responden <span
                        class="text-red-500">*</span></label>
                <select wire:model="respondentCategory"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500"
                    required>
                    <option value="">Pilih Kategori</option>
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="dosen">Dosen</option>
                    <option value="tendik">Tendik</option>
                </select>
                @error('respondentCategory')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Form Fields --}}
            @if (count($formFields) > 0)
                <div>
                    <h2 class="text-lg font-semibold text-navy-700 mb-3">Data Responden</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($formFields as $field)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $field['field_label'] }}
                                    @if ($field['is_required'])
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                @if ($field['field_type'] === 'select')
                                    <select wire:model="formData.{{ $field['field_name'] }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500">
                                        <option value="">Pilih...</option>
                                        @foreach ($field['field_options'] ?? [] as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                @elseif($field['field_type'] === 'textarea')
                                    <x-textarea wire:model="formData.{{ $field['field_name'] }}" rows="3"
                                        placeholder="{{ $field['field_label'] }}" />
                                @else
                                    <x-text-input wire:model="formData.{{ $field['field_name'] }}"
                                        id="{{ $field['field_name'] }}" name="{{ $field['field_name'] }}"
                                        type="{{ $field['field_type'] }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500"
                                        placeholder="{{ $field['field_label'] }}" autocomplete="off" />
                                @endif
                                @error('formData.' . $field['field_name'])
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Survey Questions --}}
            @if (count($questions) > 0)
                <div>
                    <h2 class="text-lg font-semibold text-navy-700 mb-3">Pertanyaan Survey</h2>
                    <div class="space-y-6">
                        @foreach ($questions as $idx => $q)
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 relative">
                                <label class="block text-base font-medium text-gray-800 mb-4">
                                    <span
                                        class="w-7 h-7 flex justify-center items-center rounded-full bg-navy-100 text-navy-700 font-semibold mr-2 text-center align-middle absolute -top-2 -left-2">{{ $idx + 1 }}</span>
                                    {{ $q['question_text'] }}
                                    @if ($q['is_required'])
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                @if ($q['question_type'] === 'rating')
                                    <div class="flex justify-between flex-wrap gap-3">
                                        @for ($i = $q['min_value']; $i <= $q['max_value']; $i++)
                                            <label class="flex flex-col items-center cursor-pointer">
                                                <input type="radio" wire:model="answers.{{ $q['id'] }}"
                                                    value="{{ $i }}" class="accent-navy-600 w-5 h-5">
                                                <span class="text-xs mt-1">{{ $q['rating_labels'][$i] ?? $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                @elseif($q['question_type'] === 'multiple_choice')
                                    <div class="flex flex-col gap-2">
                                        @foreach ($q['options'] ?? [] as $opt)
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" wire:model="answers.{{ $q['id'] }}"
                                                    value="{{ $opt }}" class="accent-navy-600">
                                                <span>{{ $opt }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif($q['question_type'] === 'checkbox')
                                    <div class="flex flex-col gap-2">
                                        @foreach ($q['options'] ?? [] as $opt)
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox"
                                                    wire:model="answers.{{ $q['id'] }}.{{ $loop->index }}"
                                                    value="{{ $opt }}" class="accent-navy-600">
                                                <span>{{ $opt }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <input type="text" wire:model="answers.{{ $q['id'] }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500">
                                @endif
                                @error('answers.' . $q['id'])
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="pt-4">
                <x-primary-button type="submit" class="w-full">
                    Kirim Jawaban
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
