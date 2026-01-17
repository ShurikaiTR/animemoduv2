@props([
    'variant' => 'rect', // rect, circle, text
    'width' => 'w-full',
    'height' => 'h-4',
    'class' => '',
])
@php
    $baseClasses = 'animate-pulse bg-white/5';

    $variants = [
        'rect' => 'rounded-xl',
        'circle' => 'rounded-full',
        'text' => 'rounded-md',
    ];

    $variantClass = $variants[$variant] ?? $variants['rect'];
    $classes = "{$baseClasses} {$variantClass} {$width} {$height} {$class}";
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}></div>
