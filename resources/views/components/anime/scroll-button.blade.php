@props(['direction' => 'right'])

@php
    $label = $direction === 'left' ? 'Önceki' : 'Sonraki';
@endphp

<button {{ $attributes }}
    class="w-10 h-10 rounded-full bg-bg-secondary border border-white/5 flex items-center justify-center text-white hover:bg-primary hover:text-white transition-all active:scale-95"
    aria-label="{{ $label }}">
    @if($direction === 'left')
        <x-icons.chevron-left class="w-5 h-5" />
    @else
        <x-icons.chevron-right class="w-5 h-5" />
    @endif
</button>