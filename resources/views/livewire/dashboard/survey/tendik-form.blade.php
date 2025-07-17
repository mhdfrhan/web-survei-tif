<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @include('components.alert')
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-navy-800">Survey Tendik Configuration</h1>
                <p class="text-navy-600 mt-2">Kelola survey pengukuran pemahaman Tendik Fakultas Ilmu Komputer</p>
            </div>
            <div class="flex space-x-3">
                <x-primary-button wire:click="saveAllData" class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Simpan Semua</span>
                </x-primary-button>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-gray-200 mb-8">
        <nav class="-mb-px flex space-x-8">
            <button wire:click="$set('activeTab', 'info')"
                class="py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'info' ? 'border-navy-500 text-navy-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Informasi Survey
            </button>
            <button wire:click="$set('activeTab', 'fields')"
                class="py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'fields' ? 'border-navy-500 text-navy-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Form Fields ({{ count($formFields) }})
            </button>
            <button wire:click="$set('activeTab', 'questions')"
                class="py-2 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'questions' ? 'border-navy-500 text-navy-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Survey Questions ({{ count($questions) }})
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <!-- Info Tab -->
        @if ($activeTab === 'info')
            <div class="p-8">
                <h2 class="text-xl font-semibold text-navy-800 mb-6">Informasi Survey</h2>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Survey</label>
                        <input type="text" wire:model="title"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500 transition-colors duration-200"
                            placeholder="Masukkan judul survey">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Survey</label>
                        <textarea wire:model="description" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500 transition-colors duration-200"
                            placeholder="Masukkan deskripsi survey (opsional)"></textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" wire:model="isActive" id="isActive"
                            class="h-4 w-4 text-navy-600 focus:ring-navy-500 border-gray-300 rounded">
                        <label for="isActive" class="ml-2 block text-sm text-gray-700">Survey aktif</label>
                    </div>

                    <div class="pt-4">
                        <x-primary-button wire:click="saveSurveyInfo">
                            Simpan Informasi
                        </x-primary-button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Fields Tab -->
        @if ($activeTab === 'fields')
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-navy-800">Form Fields</h2>
                    <x-primary-button wire:click="openFormFieldModal" x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'add-form-field-modal')"
                        class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Tambah Field</span>
                    </x-primary-button>
                </div>

                @if (count($formFields) > 0)
                    <div class="space-y-4">
                        @foreach ($formFields as $index => $field)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-navy-800">{{ $field['field_label'] }}</h3>
                                        <p class="text-sm text-gray-600">
                                            Type: {{ $fieldTypes[$field['field_type']] ?? $field['field_type'] }}
                                            @if ($field['is_required'])
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">Field name: {{ $field['field_name'] }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button wire:click="editFormField({{ $index }})"
                                            class="text-navy-600 hover:text-navy-800 p-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button wire:click="deleteFormField({{ $index }})"
                                            class="text-red-600 hover:text-red-800 p-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="text-gray-500">Belum ada form fields. Tambahkan field untuk mulai membuat form.</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Questions Tab -->
        @if ($activeTab === 'questions')
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-navy-800">Survey Questions</h2>
                    <div class="flex gap-2">
                        <x-primary-button wire:click="openSectionModal" x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'add-section-modal')"
                            class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Tambah Section</span>
                        </x-primary-button>
                        <x-primary-button wire:click="openQuestionModal" x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'add-question-modal')"
                            class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Tambah Question</span>
                        </x-primary-button>
                    </div>
                </div>

                @if (count($sections) > 0)
                    @foreach ($sections as $secIdx => $section)
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-navy-700 mb-2">
                                {{ chr(65 + $secIdx) }}. {{ $section['section_title'] }}
                            </h3>
                            @if (!empty($section['section_description']))
                                <p class="text-sm text-gray-500 mb-3">{{ $section['section_description'] }}</p>
                            @endif

                            @php
                                $sectionQuestions = collect($questions)
                                    ->where('survey_section_id', $section['id'])
                                    ->sortBy('sort_order')
                                    ->values();
                            @endphp

                            @if ($sectionQuestions->count() > 0)
                                <div class="space-y-4">
                                    @foreach ($sectionQuestions as $index => $question)
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h4 class="font-medium text-navy-800 mb-2">
                                                        {{ $index + 1 . '. ' . $question['question_text'] }}
                                                    </h4>
                                                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                                                        <span class="bg-navy-100 text-navy-800 px-2 py-1 rounded">
                                                            {{ $questionTypes[$question['question_type']] ?? $question['question_type'] }}
                                                        </span>
                                                        @if ($question['question_type'] === 'rating')
                                                            <span>Range: {{ $question['min_value'] }} -
                                                                {{ $question['max_value'] }}</span>
                                                        @endif
                                                        @if ($question['is_required'])
                                                            <span class="text-red-500">Required</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button
                                                        wire:click="editQuestion({{ array_search($question, $questions) }})"
                                                        class="text-navy-600 hover:text-navy-800 p-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <button
                                                        wire:click="deleteQuestion({{ array_search($question, $questions) }})"
                                                        class="text-red-600 hover:text-red-800 p-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-gray-500 italic">Belum ada pertanyaan pada section ini.</div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-12">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <p class="text-gray-500">Belum ada section. Tambahkan section untuk mulai membuat pertanyaan.
                        </p>
                    </div>
                @endif
            </div>
        @endif
    </div>


    <x-modal name="add-form-field-modal" :show="$showFormFieldModal" focusable align="center">
        <form wire:submit.prevent="saveFormField">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Field Name</label>
                    <input type="text" wire:model="newFormField.field_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500"
                        placeholder="field_name">
                    @error('newFormField.field_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Field Label</label>
                    <input type="text" wire:model="newFormField.field_label"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500"
                        placeholder="Label yang ditampilkan">
                    @error('newFormField.field_label')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Field Type</label>
                    <select wire:model="newFormField.field_type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500">
                        @foreach ($fieldTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($newFormField['field_type'] === 'select')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan (opsional)</label>
                        <div class="space-y-2">
                            @foreach ($newFormField['field_options'] as $optIndex => $option)
                                <div class="flex items-center space-x-2">
                                    <input type="text" wire:model="newFormField.field_options.{{ $optIndex }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500"
                                        placeholder="Pilihan {{ $optIndex + 1 }}">
                                    <button type="button"
                                        wire:click="removeSelectOption('newFormField', {{ $optIndex }})"
                                        class="text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addSelectOption('newFormField')"
                                class="mt-2 text-navy-600 hover:text-navy-800 text-sm flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Tambah Pilihan</span>
                            </button>
                        </div>
                    </div>
                @endif
                <div class="flex items-center space-x-3">
                    <input type="checkbox" wire:model="newFormField.is_required" id="isRequired"
                        class="h-4 w-4 text-navy-600 focus:ring-navy-500 border-gray-300 rounded">
                    <label for="isRequired" class="block text-sm text-gray-700">Field wajib diisi</label>
                </div>
                <div class="flex justify-end space-x-2 pt-4">
                    <x-secondary-button type="button" x-on:click="$dispatch('close')">Batal</x-secondary-button>
                    <x-primary-button type="submit">Simpan Field</x-primary-button>
                </div>
            </div>
        </form>
    </x-modal>

    <x-modal name="add-section-modal" :show="$showSectionModal" focusable align="center">
        <form wire:submit.prevent="saveSection">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul Section</label>
                    <input type="text" wire:model="newSection.section_title"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500"
                        placeholder="Judul section">
                    @error('newSection.section_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Section</label>
                    <textarea wire:model="newSection.section_description" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500"
                        placeholder="Deskripsi section (opsional)"></textarea>
                </div>
                <div class="flex justify-end space-x-2 pt-4">
                    <x-secondary-button type="button" x-on:click="$dispatch('close')">Batal</x-secondary-button>
                    <x-primary-button type="submit">Simpan Section</x-primary-button>
                </div>
            </div>
        </form>
    </x-modal>

    <!-- Question Modal -->
    <x-modal name="add-question-modal" :show="$showQuestionModal" focusable align="center">
        <form wire:submit.prevent="saveQuestion">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Section Survey</label>
                    <select wire:model="newQuestion.survey_section_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500">
                        <option value="">Pilih Section</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section['id'] }}">{{ $section['section_title'] }}</option>
                        @endforeach
                    </select>
                    @error('newQuestion.survey_section_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pertanyaan</label>
                    <input type="text" wire:model="newQuestion.question_text"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500"
                        placeholder="Tulis pertanyaan">
                    @error('newQuestion.question_text')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Pertanyaan</label>
                    <select wire:model="newQuestion.question_type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500">
                        @foreach ($questionTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($newQuestion['question_type'] === 'rating')
                    <div class="flex space-x-2">
                        <div>
                            <label class="block text-xs text-gray-600">Min</label>
                            <input type="number" wire:model="newQuestion.min_value" min="1" max="10"
                                class="w-16 px-2 py-1 border rounded">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600">Max</label>
                            <input type="number" wire:model="newQuestion.max_value" min="1" max="10"
                                class="w-16 px-2 py-1 border rounded">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mt-2">Label Skala (opsional)</label>
                        @for ($i = $newQuestion['min_value']; $i <= $newQuestion['max_value']; $i++)
                            <input type="text" wire:model="newQuestion.rating_labels.{{ $i }}"
                                class="w-full px-2 py-1 border rounded mb-1"
                                placeholder="Label untuk nilai {{ $i }}">
                        @endfor
                    </div>
                @elseif ($newQuestion['question_type'] === 'multiple_choice' || $newQuestion['question_type'] === 'checkbox')
                    <div>
                        <label class="block text-xs text-gray-600">Pilihan Jawaban</label>
                        <div class="space-y-2">
                            @foreach ($newQuestion['options'] as $optIndex => $option)
                                <div class="flex items-center space-x-2">
                                    <input type="text" wire:model="newQuestion.options.{{ $optIndex }}"
                                        class="w-full px-2 py-1 border rounded"
                                        placeholder="Pilihan {{ $optIndex + 1 }}">
                                    <button type="button"
                                        wire:click="removeMultipleChoiceOption({{ $optIndex }})"
                                        class="text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addMultipleChoiceOption"
                                class="mt-2 text-navy-600 hover:text-navy-800 text-sm flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Tambah Pilihan</span>
                            </button>
                        </div>
                    </div>
                @endif
                <div class="flex items-center space-x-3">
                    <input type="checkbox" wire:model="newQuestion.is_required" id="isRequiredQuestion"
                        class="h-4 w-4 text-navy-600 focus:ring-navy-500 border-gray-300 rounded">
                    <label for="isRequiredQuestion" class="block text-sm text-gray-700">Pertanyaan wajib diisi</label>
                </div>
                <div class="flex justify-end space-x-2 pt-4">
                    <x-secondary-button type="button" x-on:click="$dispatch('close')">Batal</x-secondary-button>
                    <x-primary-button type="submit">Simpan Pertanyaan</x-primary-button>
                </div>
            </div>
        </form>
    </x-modal>
</div>
