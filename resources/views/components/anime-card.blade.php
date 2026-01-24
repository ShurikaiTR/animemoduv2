@props([
    'anime',
    'class' => '',
])

@php
    $posterPath = $anime->poster_path;
    $isExternal = $posterPath && str_starts_with($posterPath, 'http');
    
    $imageUrl = $posterPath 
        ? ($isExternal ? $posterPath : 'https://image.tmdb.org/t/p/w780' . $posterPath)
        : asset('images/placeholder.jpg');
    
    $imageW300 = $posterPath && !$isExternal ? 'https://image.tmdb.org/t/p/w300' . $posterPath : null;
    $imageW500 = $posterPath && !$isExternal ? 'https://image.tmdb.org/t/p/w500' . $posterPath : null;

    $rating = number_format($anime->vote_average ?? 0, 1);
    $year = $anime->release_date ? $anime->release_date->format('Y') : '';
    $genres = collect($anime->genres ?? [])->take(2);
    $extraGenresCount = max(0, count($anime->genres ?? []) - 2);
    $route = route('anime.show', ['slug' => $anime->slug]);
@endphp

<div {{ $attributes->merge(['class' => 'group relative w-full aspect-[2/3] rounded-2xl overflow-hidden cursor-pointer ' . $class]) }}>
    {{-- Year Badge --}}
    @if($year)
    <div class="absolute top-3 left-3 z-20 px-2 py-1 rounded-lg bg-black/60 backdrop-blur-md border border-white/10 shadow-lg">
        <span class="text-xs font-bold text-white">{{ $year }}</span>
    </div>
    @endif

    <a href="{{ $route }}" class="block w-full h-full relative" wire:navigate>
        {{-- Image with srcset --}}
        <img 
            src="{{ $imageUrl }}" 
            @if($imageW300 && $imageW500)
                srcset="{{ $imageW300 }} 300w, {{ $imageW500 }} 500w, {{ $imageUrl }} 780w"
                sizes="(max-width: 640px) 50vw, (max-width: 1024px) 33vw, 20vw"
            @endif
            alt="{{ $anime->title }}"
            loading="lazy"
            class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
        />

        {{-- Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-80 group-hover:opacity-60 transition-opacity duration-500"></div>
        
        {{-- Shine Effect --}}
        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none shadow-[inset_0_0_80px_rgba(0,0,0,0.8)]"></div>

        {{-- Rating Badge --}}
        <div class="absolute top-3 right-3 bg-black/60 backdrop-blur-md rounded-full border border-white/10 shadow-xl">
            <x-anime.rating-circle :rating="$rating" size="sm" />
        </div>

        {{-- Play Button Overlay --}}
        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 z-10">
            <div class="w-16 h-16 rounded-full bg-primary/20 backdrop-blur-sm border border-primary/50 flex items-center justify-center shadow-[0_0_30px_rgba(47,128,237,0.4)] group-hover:scale-100 scale-50 transition-transform duration-500">
                <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center shadow-lg text-white">
                    <x-icons.play class="w-5 h-5 ml-1 fill-current" />
                </div>
            </div>
        </div>

        {{-- Content Info --}}
        <div class="absolute bottom-0 left-0 right-0 p-5 translate-y-2 group-hover:translate-y-0 transition-transform duration-500 ease-out z-20">
            <h3 title="{{ $anime->title }}" class="text-lg font-bold text-white font-rubik leading-tight mb-2 line-clamp-2 drop-shadow-lg group-hover:!text-primary transition-colors duration-300">
                {{ $anime->title }}
            </h3>

            <div class="flex items-center justify-between text-xs text-white/70 font-medium">
                <div class="flex items-center gap-1.5 flex-wrap">
                    @forelse($genres as $genre)
                        <span class="text-primary font-semibold">
                            @if($genre instanceof \App\Enums\AnimeGenre)
                                {{ $genre->label() }}
                            @elseif(is_string($genre))
                                {{ \App\Enums\AnimeGenre::tryFrom($genre)?->label() ?? $genre }}
                            @else
                                {{ $genre }}
                            @endif
                            @if(!$loop->last),@endif
                        </span>
                    @empty
                        <span class="text-primary font-semibold">Anime</span>
                    @endforelse

                    @if($extraGenresCount > 0)
                        <span class="text-primary font-semibold">+{{ $extraGenresCount }}</span>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- Hover Border --}}
        <div class="absolute inset-0 border-2 border-primary/0 group-hover:border-primary/50 rounded-2xl transition-colors duration-500 pointer-events-none"></div>
    </a>
</div>
