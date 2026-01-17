@props([
    'icon' => 'heroicon-o-information-circle',
    'title' => 'Sonuç Bulunamadı',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'py-20 text-center flex flex-col items-center justify-center']) }}>
    {{-- Icon --}}
    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-6">
        @if(is_string($icon))
            <x-dynamic-component :component="$icon" class="w-8 h-8 text-white/20" />
        @else
            {{ $icon }}
        @endif
    </div>

    {{-- Text --}}
    <h3 class="text-xl font-bold text-white mb-2">
        {{ $title }}
    </h3>

    @if($description)
        <p class="text-white/40 max-w-xs mx-auto leading-relaxed">
            {{ $description }}
        </p>
    @endif

    {{-- Actions Slot --}}
    @if($slot->isNotEmpty())
        <div class="mt-8">
            {{ $slot }}
        </div>
    @endif
</div>
