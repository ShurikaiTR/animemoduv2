@props([
    'sort',
    'genres',
    'search',
    'availableGenres',
])

<div {{ $attributes->merge(['class' => 'w-full mb-6']) }}>
    <div class="flex items-center gap-3 overflow-x-auto pb-4 scrollbar-hide">
        {{-- Search Input --}}
        <div class="relative min-w-[200px]">
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                placeholder="Anime ara..." 
                class="w-full pl-9 pr-4 py-2 bg-bg-secondary rounded-xl border border-white/10 text-sm text-white focus:outline-none focus:border-primary/50 transition-colors"
            >
            <x-icons.search class="w-4 h-4 text-text-main absolute left-3 top-1/2 -translate-y-1/2" />
        </div>

        {{-- Sort Dropdown --}}
        <select 
            wire:model.live="sort" 
            class="px-4 py-2 bg-bg-secondary rounded-xl border border-white/10 text-sm text-white focus:outline-none focus:border-primary/50 transition-colors appearance-none"
        >
            <option value="yeni">Yeni Eklenenler</option>
            <option value="populer">Popüler</option>
            <option value="puan">Puan</option>
            <option value="eski">Eskiler</option>
        </select>
    </div>
    
    {{-- Genre Chips --}}
    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
        <button 
            wire:click="toggleGenre('hepsi')"
            class="px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition-all duration-300 {{ empty($genres) ? 'bg-primary text-white shadow-[0_0_15px_rgba(var(--primary),0.4)]' : 'bg-bg-secondary text-text-main hover:text-white border border-white/5' }}"
        >
            Hepsi
        </button>
        @foreach($availableGenres as $genreEnum)
            @php
                $isActive = in_array($genreEnum->value, $genres);
            @endphp
            <button 
                wire:click="toggleGenre('{{ $genreEnum->value }}')"
                class="px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition-all duration-300 {{ $isActive ? 'bg-primary text-white shadow-[0_0_15px_rgba(var(--primary),0.4)]' : 'bg-bg-secondary text-text-main hover:text-white border border-white/5' }}"
            >
                {{ $genreEnum->label() }}
            </button>
        @endforeach
    </div>
</div>
