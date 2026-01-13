@props([
    'type' => 'text',
])

<input
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'flex h-10 w-full rounded-2xl border-none bg-bg-secondary px-4 py-2 text-sm text-white placeholder:text-text-main/30 focus:outline-none focus:ring-1 focus:ring-primary/50 transition-all duration-300 disabled:cursor-not-allowed disabled:opacity-50'
    ]) }}
/>
