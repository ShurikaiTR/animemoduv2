@props(['anime'])

@inject('tmdb', 'App\Services\TmdbService')

@php
    $imageUrl = $tmdb->getImageUrl($anime->backdrop_path ?? $anime->poster_path, 'w1280');
    $year = $tmdb->getYear($anime->release_date?->format('Y-m-d'));
    $rating = (float) ($anime->vote_average ?? 0);
    $genres = is_array($anime->genres) ? $anime->genres : [];
    $href = route('anime.show', $anime->slug);
@endphp

<div x-data="{ open: false, showTrailer: false }">
    <div class="relative h-hero lg:h-hero-lg w-full flex items-end group/hero sm:rounded-4xl">
        {{-- Background Wrapper --}}
        <div class="absolute inset-0 overflow-hidden sm:rounded-4xl sm:shadow-2xl sm:ring-1 sm:ring-white/10">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $anime->title }}"
                    class="w-full h-full object-cover select-none transform scale-105 transition-transform duration-700 group-hover/hero:scale-110 aspect-video"
                    fetchpriority="high" decoding="async" />
            @endif
            {{-- Bottom gradient (for buttons/content) --}}
            <div class="absolute inset-0 bg-linear-to-t from-black via-black/60 to-transparent z-10"></div>
            {{-- Left-to-Right gradient (Netflix technique) --}}
            <div class="absolute inset-0 bg-linear-to-r from-black via-black/60 to-transparent z-10"></div>
        </div>

        {{-- Content --}}
        <div class="relative z-20 w-full p-8 md:p-12 lg:p-16 mb-4">
            <div class="max-w-4xl flex flex-col gap-6">

                {{-- Title --}}
                <a href="{{ $href }}" class="block w-fit">
                    <h1
                        class="text-4xl md:text-6xl lg:text-7xl font-rubik font-black text-white leading-none tracking-tighter uppercase drop-shadow-2xl hover:text-primary transition-colors">
                        @php $titleParts = explode(' ', $anime->title, 2); @endphp
                        {{ $titleParts[0] }}
                        @if(isset($titleParts[1]))
                            <br class="hidden sm:block" /> <span class="text-primary">{{ $titleParts[1] }}</span>
                        @endif
                    </h1>
                </a>

                {{-- Metadata Bar --}}
                <div class="flex flex-wrap items-center gap-6">
                    <x-anime.rating-circle :rating="$rating" size="md" />

                    <div class="flex items-center gap-2">
                        @if($anime->status)
                            @php
                                $status = $anime->status instanceof \App\Enums\AnimeStatus ? $anime->status : null;
                                $badgeClasses = match ($status) {
                                    \App\Enums\AnimeStatus::ONGOING => 'bg-success/20 text-success border-success/30',
                                    \App\Enums\AnimeStatus::COMPLETED => 'bg-primary/20 text-primary border-primary/30',
                                    default => 'bg-primary/20 text-primary border-primary/30',
                                };
                            @endphp
                            <span
                                class="px-3 py-1 rounded {{ $badgeClasses }} text-xs font-bold border uppercase tracking-wide">
                                {{ $anime->status->value }}
                            </span>
                        @endif
                        <span
                            class="px-2 py-1 rounded bg-white/10 text-gray-200 text-xs font-medium border border-white/10">
                            {{ $year }}
                        </span>
                        <span
                            class="px-2 py-1 rounded bg-white/10 text-gray-200 text-xs font-medium border border-white/10 uppercase">
                            HD | 1080P
                        </span>
                    </div>
                </div>

                {{-- Description --}}
                @if($anime->overview)
                    <p
                        class="text-gray-200 text-base md:text-lg line-clamp-2 max-w-2xl leading-relaxed drop-shadow-md font-inter opacity-90">
                        {{ $anime->overview }}
                    </p>
                @endif

                {{-- Genres --}}
                <div class="flex gap-2 text-sm text-gray-300 font-medium font-inter">
                    @foreach(array_slice($genres, 0, 3) as $genre)
                        <span class="hover:text-primary cursor-pointer transition-colors">{{ $genre }}</span>
                        @if(!$loop->last)
                            <span class="text-primary self-center opacity-70">•</span>
                        @endif
                    @endforeach
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mt-4">
                    <x-ui.button tag="a" href="{{ $href }}" size="lg" class="w-full sm:w-auto">
                        <x-icons.play class="w-5 h-5 fill-current" />
                        Hemen İzle
                    </x-ui.button>

                    <div class="flex items-center gap-3 h-full">
                        @if ($anime->trailer_key)
                            <x-ui.button variant="glass" size="lg" @click="showTrailer = true" class="flex-1 sm:flex-none">
                                <x-icons.movie class="w-5 h-5" />
                                <span class="ml-2">Fragman</span>
                            </x-ui.button>
                        @endif

                        <x-anime.watch-status-dropdown position="top"
                            class="flex-1 sm:flex-none w-full sm:w-auto h-14 bg-white/10 hover:bg-primary/20 backdrop-blur-sm border border-white/10 text-white hover:text-primary hover:border-primary/40 px-6 rounded-2xl font-bold font-rubik transition-all duration-300 active:scale-95 group">
                            <x-slot:trigger>
                                <x-icons.bookmark-plus class="w-5 h-5 group-hover:text-primary transition-colors" />
                                <span class="hidden sm:inline ml-2">Listeye Ekle</span>
                                <span class="sm:hidden ml-2">Listem</span>
                            </x-slot:trigger>
                        </x-anime.watch-status-dropdown>

                        {{-- Favorite Button --}}
                        <x-ui.button variant="glass-danger" size="icon" class="h-14 w-14 shrink-0"
                            aria-label="Favorilere ekle">
                            <x-icons.heart class="w-6 h-6 transition-all duration-300" />
                        </x-ui.button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Trailer Modal --}}
    @if ($anime->trailer_key)
        <x-anime.trailer-modal :trailerKey="$anime->trailer_key" :title="$anime->title" />
    @endif
</div>