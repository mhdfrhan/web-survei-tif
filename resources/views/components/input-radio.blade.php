@props([
    'name',
    'options' => [],
    'selected' => null,
    'inline' => false,
    'disabled' => false,
    'required' => false,
])

<div {{ $attributes->merge(['class' => $inline ? 'flex flex-wrap gap-4' : 'flex flex-col gap-2']) }}>
    @foreach($options as $value => $label)
        <label class="flex items-center gap-2 cursor-pointer">
            <input
                type="radio"
                name="{{ $name }}"
                value="{{ $value }}"
                @if($required) required @endif
                @if($disabled) disabled @endif
                {{ $attributes->whereStartsWith('wire:model') }}
                @checked(old($name, $selected) == $value)
                class="accent-navy-600"
            >
            <span>{{ $label }}</span>
        </label>
    @endforeach
</div>