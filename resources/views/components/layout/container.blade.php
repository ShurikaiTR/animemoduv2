@props(['className' => ''])

<div {{ $attributes->merge(['class' => 'container mx-auto px-4 sm:px-8 ' . $className]) }}>
    {{ $slot }}
</div>