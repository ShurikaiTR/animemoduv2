@props([
    'variant' => 'primary',
    'size' => 'md',
    'tag' => 'button',
])
@php
    $variants = [
        'primary' => 'bg-primary text-white hover:scale-105 active:scale-95 before:absolute before:inset-0 before:bg-gradient-to-r before:from-transparent before:via-white/20 before:to-transparent before:-translate-x-full hover:before:translate-x-full before:transition-transform before:duration-700 before:z-0',
        'secondary' => 'bg-bg-secondary text-white border border-white/5 hover:bg-white/5 active:scale-95',
        'outline' => 'bg-transparent border border-primary text-primary hover:bg-primary hover:text-white active:scale-95',
        'ghost' => 'bg-transparent text-text-main hover:bg-white/5 active:scale-95',
        'link' => 'bg-transparent text-text-main hover:text-primary',
        'danger' => 'bg-danger text-white hover:bg-danger/90 active:scale-95',
        'success' => 'bg-success text-white hover:scale-105 active:scale-95 before:absolute before:inset-0 before:bg-gradient-to-r before:from-transparent before:via-white/20 before:to-transparent before:-translate-x-full hover:before:translate-x-full before:transition-transform before:duration-700 before:z-0',
        'orange' => 'bg-orange text-white hover:scale-105 active:scale-95 before:absolute before:inset-0 before:bg-gradient-to-r before:from-transparent before:via-white/20 before:to-transparent before:-translate-x-full hover:before:translate-x-full before:transition-transform before:duration-700 before:z-0',
        'glass' => 'bg-white/10 hover:bg-primary/20 backdrop-blur-sm border border-white/10 text-white hover:text-primary hover:border-primary/40 active:scale-95 transition-all duration-300',
        'glass-success' => 'bg-white/10 hover:bg-success/20 backdrop-blur-sm border border-white/10 text-white hover:text-success hover:border-success/40 active:scale-95 transition-all duration-300',
        'glass-danger' => 'bg-white/10 hover:bg-danger/20 backdrop-blur-sm border border-white/10 text-white hover:text-danger hover:border-danger/40 active:scale-95 transition-all duration-300',
    ];

    $sizes = [
        'sm' => 'h-8 px-4 text-xs',
        'md' => 'h-11 px-6 text-sm',
        'lg' => 'h-14 px-8 text-base',
        'icon' => 'size-11 flex items-center justify-center',
    ];

    $classes = $variants[$variant] ?? $variants['primary'];
    $classes .= ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => "group relative overflow-hidden inline-flex items-center justify-center rounded-2xl font-bold font-rubik transition-all duration-300 disabled:opacity-50 disabled:pointer-events-none {$classes}"]) }}>
    <span class="relative z-10 flex items-center gap-2">
        {{ $slot }}
    </span>
</{{ $tag }}>
