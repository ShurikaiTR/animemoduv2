@props(['rating', 'size' => 'md'])

@php
    $sizeClasses = match ($size) {
        'sm' => 'w-10 h-10 text-xs',
        'md' => 'w-12 h-12 text-sm',
        'lg' => 'w-16 h-16 text-base',
        default => 'w-12 h-12 text-sm',
    };
    $ratingValue = (float) ($rating ?? 0);
@endphp

<div class="relative {{ $sizeClasses }} flex items-center justify-center">
    <svg viewBox="0 0 36 36" class="w-full h-full text-primary -rotate-90">
        {{-- Background circle --}}
        <path class="text-primary/20" stroke-width="3" stroke="currentColor" fill="none"
            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
        {{-- Progress circle --}}
        <path class="text-current drop-shadow-glow" stroke-dasharray="{{ $ratingValue * 10 }}, 100" stroke-width="3"
            stroke-linecap="round" stroke="currentColor" fill="none"
            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
    </svg>
    <div class="absolute inset-0 flex items-center justify-center font-bold text-white font-rubik">
        {{ number_format($ratingValue, 1) }}
    </div>
</div>