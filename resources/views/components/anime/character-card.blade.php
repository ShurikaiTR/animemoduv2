@props(['name', 'character', 'image' => null])

<div
    class="group relative w-full aspect-poster rounded-2xl overflow-hidden cursor-pointer border border-white/5 bg-bg-secondary">
    {{-- Character Image --}}
    @if($image)
        <img src="{{ $image }}" alt="{{ $name }} - {{ $character }}" loading="lazy"
            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105" />
    @else
        <div class="absolute inset-0 flex items-center justify-center bg-gray-800">
            <x-icons.user class="w-12 h-12 text-white/10" />
        </div>
    @endif

    {{-- Gradient Overlay --}}
    <div
        class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-60 group-hover:opacity-80 transition-opacity duration-500">
    </div>

    {{-- Hover Border --}}
    <div
        class="absolute inset-0 border-2 border-primary/0 group-hover:border-primary/50 rounded-2xl transition-colors duration-500 pointer-events-none">
    </div>

    {{-- Content --}}
    <div
        class="absolute bottom-0 left-0 right-0 p-4 translate-y-2 group-hover:translate-y-0 transition-transform duration-500 ease-out z-20">
        <h4
            class="text-white font-bold text-sm leading-tight mb-1 group-hover:text-primary transition-colors line-clamp-2">
            {{ $name }}
        </h4>
        <p class="text-white/70 text-xs font-medium truncate">
            {{ $character }}
        </p>
    </div>
</div>