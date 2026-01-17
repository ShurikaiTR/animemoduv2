@props(['anime', 'trailer' => null, 'watchUrl' => '#episode-list'])

@inject('tmdb', 'App\Services\TmdbService')

@php
    $backdrop = $tmdb->getImageUrl($anime->backdrop_path ?? $anime->poster_path, 'w780');
    $rating = (float) ($anime->vote_average ?? 0);
    $genres = is_array($anime->genres) ? $anime->genres : [];
    $year = $tmdb->getYear($anime->release_date?->format('Y-m-d'));
@endphp

<div x-data="{ showTrailer: false }">
    <section aria-labelledby="anime-title"
        class="section section--head section--head-fixed section--gradient section--details-bg relative pb-16 pt-60 -mt-24 bg-bg-main">
        {{-- Backdrop Image --}}
        <div class="absolute top-0 left-0 right-0 h-96 w-full z-0">
            @if($backdrop)
                <img src="{{ $backdrop }}" alt="{{ $anime->title }} arkaplan görseli"
                    class="absolute inset-0 w-full h-full object-cover opacity-40 select-none" loading="eager"
                    fetchpriority="high" decoding="async" />
            @endif
            <div class="absolute inset-0 bg-gradient-to-b from-bg-main/30 via-bg-main/80 to-bg-main z-10"></div>
        </div>

        <x-layout.container class="z-20 relative">
            <div class="flex flex-col xl:flex-row xl:items-start gap-8">
                <div class="flex-1 min-w-0">
                    <div class="article__content flex flex-col mb-10">
                        {{-- Title --}}
                        <h1 id="anime-title"
                            class="text-4xl lg:text-6xl text-white font-rubik font-semibold mb-6 leading-tight tracking-tight">
                            {{ $anime->title }}
                        </h1>

                        {{-- Metadata --}}
                        <ul class="flex flex-wrap items-center gap-6 mb-8 text-text-main font-inter text-base">
                            <li class="flex items-center gap-1.5 text-white">
                                <x-icons.star class="w-5 h-5 fill-primary text-primary" />
                                {{ number_format($rating, 1) }}
                            </li>
                            @if($anime->status)
                                @php
                                    $statusColor = $anime->status instanceof \App\Enums\AnimeStatus ? $anime->status->getColor() : 'primary';
                                @endphp
                                <li class="flex items-center">
                                    <span
                                        class="px-2.5 py-0.5 rounded-lg bg-{{ $statusColor }}/10 text-{{ $statusColor }} border-{{ $statusColor }}/20 text-sm font-bold border uppercase tracking-wider">
                                        {{ $anime->status->value }}
                                    </span>
                                </li>
                            @endif
                            @foreach(array_slice($genres, 0, 3) as $genre)
                                <li
                                    class="relative pl-6 before:content-[''] before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-1 before:bg-primary before:rounded-full">
                                    {{ $genre }}
                                </li>
                            @endforeach
                            <li
                                class="relative pl-6 before:content-[''] before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:w-1 before:h-1 before:bg-primary before:rounded-full">
                                {{ $year }}
                            </li>
                        </ul>

                        {{-- Description --}}
                        <div x-data="{ expanded: false }" class="mb-10 max-w-3xl">
                            <p class="text-text-main text-base leading-relaxed transition-all duration-300"
                                :class="expanded ? '' : 'line-clamp-3 md:line-clamp-none'">
                                {{ $anime->overview ?: 'Bu içerik için henüz bir özet bulunmuyor.' }}
                            </p>
                            @if(strlen($anime->overview ?? '') > 150)
                                <button type="button" @click="expanded = !expanded"
                                    class="text-primary text-sm font-bold mt-2 hover:underline md:hidden focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-bg-main rounded">
                                    <span x-show="!expanded" class="text-xs uppercase tracking-widest">Devamını Oku</span>
                                    <span x-show="expanded" class="text-xs uppercase tracking-widest">Daha Az Göster</span>
                                </button>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4 md:gap-8">
                            {{-- Hemen İzle Button --}}
                            <x-ui.button tag="a" href="{{ $watchUrl }}" variant="primary" size="lg"
                                class="w-full md:w-auto px-10 h-14 md:h-12 gap-3 group/watch cursor-pointer">
                                <x-icons.play class="w-6 h-6 fill-current" />
                                <span class="text-lg md:text-base">Hemen İzle</span>
                            </x-ui.button>

                            <div class="flex items-center gap-4 md:gap-8">
                                {{-- Trailer Button --}}
                                @if($trailer)
                                    <button type="button" @click="showTrailer = true"
                                        class="flex-1 md:flex-none inline-flex items-center justify-center md:justify-start text-white text-lg hover:text-primary transition-colors group cursor-pointer outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-bg-main rounded-lg">
                                        <div
                                            class="w-12 h-12 flex items-center justify-center rounded-full border border-white group-hover:border-primary transition-colors mr-3 md:mr-4">
                                            <x-icons.play class="w-5 h-5 fill-current ml-1" />
                                        </div>
                                        <span class="text-base md:text-lg whitespace-nowrap">Fragman</span>
                                    </button>
                                @endif

                                {{-- Status & Favorite --}}
                                <div class="flex items-center gap-3 md:gap-4">
                                    <x-anime.watch-status-dropdown position="bottom"
                                        class="w-12 h-12 rounded-full bg-white/10 hover:bg-primary/10 backdrop-blur-sm border border-white/10 text-white hover:text-primary hover:border-primary/40 transition-all duration-300 group"
                                        aria-label="Listeye ekle">
                                        <x-slot:trigger>
                                            <x-icons.bookmark-plus
                                                class="w-6 h-6 text-white group-hover:text-primary transition-colors" />
                                        </x-slot:trigger>
                                    </x-anime.watch-status-dropdown>

                                    {{-- Favorite Button --}}
                                    <x-ui.button variant="glass-danger" size="icon" class="w-12 h-12 rounded-full"
                                        aria-label="Favorilere ekle">
                                        <x-icons.heart class="w-6 h-6 transition-all duration-300" />
                                    </x-ui.button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-layout.container>
    </section>

    {{-- Trailer Modal --}}
    @if($trailer)
        <x-anime.trailer-modal :trailerKey="$trailer" :title="$anime->title" />
    @endif
</div>