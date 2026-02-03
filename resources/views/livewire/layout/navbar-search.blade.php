<?php

declare(strict_types=1);

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Anime;

new class extends Component {
    public string $query = '';

    #[Computed]
    public function results()
    {
        if (strlen($this->query) < 2) {
            return collect();
        }

        $searchTerm = $this->query;
        // Normalize: handle 'x' characters and spaces as wildcards for better matching (e.g. SPY×FAMILY)
        $flexibleSearch = str_replace([' ', 'x', 'X'], '%', $searchTerm);

        return Anime::where(function ($q) use ($searchTerm, $flexibleSearch) {
            $q->where('title', 'like', '%' . $searchTerm . '%')
                ->orWhere('title', 'like', '%' . $flexibleSearch . '%')
                ->orWhere('original_title', 'like', '%' . $searchTerm . '%')
                ->orWhere('original_title', 'like', '%' . $flexibleSearch . '%');
        })
            ->select(['id', 'title', 'slug', 'poster_path', 'release_date', 'vote_average', 'genres', 'media_type'])
            ->take(6)
            ->get();
    }
}; ?>

<div class="flex items-center" x-data="{ 
         isOpen: false, 
         showResults: false,
         close() { 
             this.isOpen = false; 
             this.showResults = false;
             $wire.set('query', '', false);
         }
     }" @click.outside="close()">

    {{-- Mobil Arama Butonu --}}
    <button type="button" @click="isOpen = !isOpen"
        class="xl:hidden flex items-center justify-center text-primary hover:text-white transition-colors duration-300"
        aria-label="Aramayı aç">
        <x-icons.search class="w-5 h-5" />
    </button>

    {{-- Arama Formu --}}
    <form wire:submit.prevent=""
        :class="{ 'opacity-100 translate-y-0 pointer-events-auto': isOpen, 'opacity-0 -translate-y-4 pointer-events-none xl:opacity-100 xl:translate-y-0 xl:pointer-events-auto': !isOpen }"
        class="xl:relative xl:w-64 xl:top-auto xl:left-auto xl:right-auto xl:bottom-auto absolute left-0 right-0 top-0 h-20 xl:h-auto bg-bg-main xl:bg-transparent flex flex-row items-center justify-start px-4 xl:px-0 transition-all duration-500 z-50 overflow-visible">
        <div class="relative w-full">
            <input wire:model.live.debounce.300ms="query" @focus="showResults = true" @input="showResults = true"
                type="text" placeholder="Anime ara..." autoComplete="off"
                class="h-10 rounded-2xl bg-bg-secondary border-none pl-5 pr-12 text-sm text-white placeholder:text-text-main/70 focus:outline-none focus:ring-0 transition-all duration-300 w-[calc(100%-2.5rem)] xl:w-full" />

            {{-- Arama İkonu --}}
            <button wire:loading.remove wire:target="query" type="submit"
                class="absolute top-1/2 -translate-y-1/2 flex items-center justify-center w-5 h-5 text-primary hover:text-white transition-colors duration-300 z-10 right-14 xl:right-4"
                aria-label="Ara">
                <x-icons.search class="w-5 h-5" />
            </button>

            {{-- Yükleniyor İkonu --}}
            <div wire:loading wire:target="query"
                class="absolute top-1/2 -translate-y-1/2 flex items-center justify-center w-5 h-5 text-primary z-10 right-14 xl:right-4 pointer-events-none">
                <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </div>

            <button type="button" @click="close()"
                class="absolute right-0 xl:hidden top-1/2 -translate-y-1/2 flex items-center justify-center w-10 h-full text-primary/70 hover:text-primary transition-opacity duration-300 z-10"
                aria-label="Aramayı kapat">
                <x-icons.close class="w-5 h-5" />
            </button>

            {{-- Arama Sonuçları --}}
            @if(strlen($this->query) >= 2)
                <div x-show="showResults" x-cloak x-transition
                    class="absolute top-full left-0 right-0 xl:right-auto mt-2 w-full xl:w-96 bg-bg-secondary/95 backdrop-blur-md rounded-xl shadow-2xl overflow-hidden z-[100]">
                    @if(count($this->results) > 0)
                        <div class="flex flex-col">
                            @foreach($this->results as $anime)
                                <a href="{{ route('anime.show', $anime->slug) }}" wire:navigate
                                    class="p-3 text-sm text-white/80 hover:bg-white/10 hover:text-white transition-all duration-300 border-b border-white/5 last:border-0 flex items-center gap-4 group">
                                    <div class="relative flex-shrink-0">
                                        <img src="https://image.tmdb.org/t/p/w92{{ $anime->poster_path }}"
                                            class="w-10 h-14 object-cover rounded-lg shadow-lg group-hover:scale-105 transition-transform duration-300"
                                            alt="{{ $anime->title }}">
                                        @if($anime->vote_average)
                                            <div
                                                class="absolute -top-1 -right-1 bg-primary text-[10px] font-bold text-white px-1 rounded-sm shadow-sm flex items-center gap-0.5">
                                                <x-heroicon-s-star class="w-2.5 h-2.5" />
                                                {{ number_format((float) $anime->vote_average, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span
                                            class="font-bold text-white truncate text-base leading-tight group-hover:text-primary transition-colors">{{ $anime->title }}</span>
                                        <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                            @if($anime->release_date)
                                                <span
                                                    class="text-xs text-white/50 font-mono">{{ $anime->release_date->format('Y') }}</span>
                                            @endif
                                            @if($anime->media_type)
                                                <span class="w-1 h-1 rounded-full bg-white/20"></span>
                                                <span
                                                    class="text-[10px] uppercase tracking-wider text-primary/80 font-bold bg-primary/10 px-1.5 py-0.5 rounded-md leading-none">
                                                    {{ $anime->media_type === 'movie' ? 'Film' : 'TV' }}
                                                </span>
                                            @endif
                                            @if($anime->genres && count($anime->genres) > 0)
                                                <span class="w-1 h-1 rounded-full bg-white/20"></span>
                                                <span class="text-xs text-white/40 truncate">
                                                    {{ collect($anime->genres)->take(2)->join(', ') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-4 text-center text-white/40 text-sm italic">
                            "{{ $this->query }}" için sonuç bulunamadı.
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </form>
</div>