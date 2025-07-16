<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' => 'block w-auto text-center border border-neutral-300 text-neutral-700 px-6 py-2.5 rounded-full text-sm font-semibold uppercase hover:bg-neutral-100 transition-all duration-300 hover:shadow-md active:scale-95 tracking-wide cursor-pointer'
    ]) }}>
    {{ $slot }}
</button>
