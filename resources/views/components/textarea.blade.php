@props([
    'rows' => 3,
])

<textarea
    {{ $attributes->merge([
        'class' => 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500'
    ]) }}
    rows="{{ $rows }}"
></textarea>