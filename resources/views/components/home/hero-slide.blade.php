@props(['anime', 'index', 'activeSlide', 'tmdbService'])

@use('App\Enums\AnimeStatus')

@php
    $backdropUrl = $anime->backdrop_path ? $tmdbService->getImageUrl($anime->backdrop_path, 'original') : asset('img/placeholder-backdrop.jpg');
    $logoUrl = $anime->logo_path ? $tmdbService->getImageUrl($anime->logo_path, 'original') : null;
@endphp

<div
    x-show="activeSlide === {{ $index }}"
    x-transition:enter="transition ease-out duration-700"
    x-transition:enter-start="opacity-0 scale-105"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-700 absolute top-0 left-0 w-full h-full"
    x-transition:leave-start="opacity-100 scale-100 z-10"
    x-transition:leave-end="opacity-0 scale-100 z-0"
    class="absolute inset-0 w-full h-full"
>
    {{-- Background Image --}}
    <div class="absolute inset-0 w-full h-full">
        <div class="w-full h-full relative">
            <img
                src="{{ $backdropUrl }}"
                alt="{{ $anime->title }}"
                @if($index === 0)
                    fetchpriority="high"
                @else
                    loading="lazy"
                @endif
                class="w-full h-full object-cover object-center brightness-110 contrast-110 saturate-110"
            />
            {{-- Gradients --}}
            {{-- Bottom Gradient --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/95 md:via-black/70 to-transparent"></div>
            {{-- Left Gradient (Desktop) --}}
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/30 to-transparent hidden md:block"></div>
            {{-- Top Vignette --}}
            <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-black/60 to-transparent opacity-40"></div>
        </div>
    </div>

    {{-- Content --}}
    <div class="absolute inset-0 flex items-end pb-16 md:pb-20 lg:pb-24 pl-4 md:pl-12 lg:pl-16 pr-4 md:pr-12 pointer-events-none">
        {{-- Left Content Area --}}
        <div 
            class="w-fit max-w-full md:max-w-4xl flex flex-col items-start gap-4 md:gap-6 z-20 pointer-events-auto"
            @mouseenter="hoveringLeft = true; isPaused = true"
            @mouseleave="hoveringLeft = false; isPaused = false"
        >
            {{-- Logo (If exists, otherwise Title) --}}
            <a href="{{ route('anime.show', $anime->slug) }}" class="block relative z-30 pointer-events-auto">
                @if($logoUrl)
                    <img
                        src="{{ $logoUrl }}"
                        alt="{{ $anime->title }}"
                        class="h-16 md:h-24 lg:h-32 object-contain w-auto max-w-4/5"
                    />
                @else
                    <h1 class="text-2xl sm:text-3xl md:text-5xl lg:text-6xl font-black text-white leading-tight drop-shadow-lg font-outfit">
                        {{ $anime->title }}
                    </h1>
                @endif
            </a>

            {{-- Meta Tags --}}
            <div class="flex flex-wrap items-center gap-2 md:gap-3 text-sm md:text-base font-medium text-gray-300">
                    {{-- Rating --}}
                <div class="flex items-center gap-1">
                    <x-anime.rating-circle :rating="$anime->vote_average ?? 0" size="md" />
                </div>
                
                @if($anime->status)
                    <span class="hidden md:inline-block w-1 h-1 rounded-full bg-gray-500"></span>
                    @php
                        $isOngoing = $anime->status === AnimeStatus::ONGOING;
                        $badgeStyle = $isOngoing 
                            ? 'bg-success text-white border-success' 
                            : 'bg-primary text-white border-primary';
                    @endphp
                    <span class="px-2.5 py-0.5 rounded {{ $badgeStyle }} text-xs font-bold border uppercase tracking-wide">
                        {{ $anime->status->value }}
                    </span>
                @endif

                    @if($anime->release_date)
                    <span class="hidden md:inline-block w-1 h-1 rounded-full bg-gray-500"></span>
                    <span class="px-2.5 py-0.5 rounded bg-white/10 text-gray-200 text-xs font-bold border border-white/20 uppercase tracking-wide">
                        {{ $anime->release_date->year }}
                    </span>
                @endif

                    @if($anime->genres)
                    <span class="hidden md:inline-block w-1 h-1 rounded-full bg-gray-500"></span>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @php
                            $genresList = is_array($anime->genres) ? $anime->genres : explode(',', (string)$anime->genres);
                        @endphp
                        @foreach(array_slice($genresList, 0, 3) as $genre)
                            <span class="text-text-main hover:text-primary transition-colors cursor-pointer whitespace-nowrap">
                                {{ trim($genre) }}
                            </span>
                            @if(!$loop->last)
                                <span class="text-gray-600 text-xs select-none -ml-1">,</span>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Description --}}
            <p class="text-sm md:text-lg text-gray-200 line-clamp-2 md:line-clamp-3 max-w-2xl leading-relaxed drop-shadow-md">
                {{ $anime->overview }}
            </p>

            <div class="flex items-center gap-3 mt-2">
                <x-ui.button tag="a" href="{{ route('anime.show', $anime->slug) }}" variant="primary" size="lg" class="px-8 font-extrabold uppercase tracking-wide">
                    <x-icons.play class="w-5 h-5 fill-current" />
                    HEMEN İZLE
                </x-ui.button>

                <x-anime.watch-status-dropdown :anime="$anime" position="top" class="h-14 font-bold uppercase tracking-wide group/list">
                    <x-slot:trigger>
                        <x-ui.button tag="span" variant="glass" size="lg" class="w-14 h-14 px-0 flex items-center justify-center rounded-2xl" aria-label="Listeye Ekle">
                            <x-icons.bookmark-plus class="w-6 h-6 group-hover/list:text-primary transition-colors" />
                        </x-ui.button>
                    </x-slot:trigger>
                </x-anime.watch-status-dropdown>
            </div>

            {{-- Pagination Indicators --}}
            <div class="flex items-center justify-start gap-2 mt-4">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
