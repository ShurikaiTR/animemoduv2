@props([
    'sort',
    'genres',
    'search',
    'availableGenres',
])

<aside {{ $attributes->merge(['class' => 'w-64 shrink-0 sticky top-32 space-y-8']) }}>
    {{-- Search --}}
    <div class="space-y-3">
        <h3 class="text-sm font-bold text-white/50 uppercase tracking-wider font-rubik">Arama</h3>
        <div class="relative group">
            <input 
                wire:model.live.debounce.300ms="search"
                type="text" 
                placeholder="Anime ara..." 
                class="w-full pl-10 pr-4 py-3 bg-bg-secondary rounded-xl border border-white/5 text-sm text-white focus:outline-none focus:border-primary/50 focus:bg-bg-secondary/80 transition-all duration-300 placeholder:text-text-main/50"
            >
            <x-icons.search class="w-5 h-5 text-text-main absolute left-3 top-1/2 -translate-y-1/2 group-focus-within:text-primary transition-colors duration-300" />
        </div>
    </div>

    {{-- Sort --}}
    <div class="space-y-3">
        <h3 class="text-sm font-bold text-white/50 uppercase tracking-wider font-rubik">Sıralama</h3>
        <div class="space-y-1">
            @foreach([
                'populer' => 'Popüler',
                'yeni' => 'En Yeni',
                'puan' => 'IMDb Puanı',
                'eski' => 'Eskiler',
            ] as $key => $label)
                <button 
                    wire:click="$set('sort', '{{ $key }}')"
                    class="w-full text-left px-3 py-2 rounded-lg text-sm transition-all flex items-center justify-between group {{ $sort === $key ? 'bg-primary/10 text-primary font-medium' : 'text-text-main hover:bg-primary/5 hover:text-primary' }}"
                >
                    {{ $label }}
                    @if($sort === $key)
                        <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    {{-- Genres --}}
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-white/50 uppercase tracking-wider font-rubik">Türler</h3>
            @if(!empty($genres))
                <button 
                    wire:click="toggleGenre('hepsi')"
                    class="text-xs text-primary hover:underline"
                >
                    Temizle
                </button>
            @endif
        </div>
        
        <div class="space-y-1 max-h-[60vh] overflow-y-auto pr-2 custom-scrollbar">
            <button 
                wire:click="toggleGenre('hepsi')"
                class="w-full text-left px-3 py-2 rounded-lg text-sm transition-all flex items-center justify-between group {{ empty($genres) ? 'bg-primary/10 text-primary font-medium' : 'text-text-main hover:bg-primary/5 hover:text-primary' }}"
            >
                Hepsi
                @if(empty($genres))
                    <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                @endif
            </button>
            @foreach($availableGenres as $genreEnum)
                @php
                    $isActive = in_array($genreEnum->value, $genres);
                @endphp
                <button 
                    wire:click="toggleGenre('{{ $genreEnum->value }}')"
                    class="w-full text-left px-3 py-2 rounded-lg text-sm transition-all flex items-center justify-between group {{ $isActive ? 'bg-primary/10 text-primary font-medium' : 'text-text-main hover:bg-primary/5 hover:text-primary' }}"
                >
                    {{ $genreEnum->label() }}
                    @if($isActive)
                        <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                    @endif
                </button>
            @endforeach
        </div>
    </div>
</aside>
