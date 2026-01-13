@props([
    'id' => null,
    'title',
    'year' => null,
    'genres' => [],
    'rating' => 0,
    'image',
    'slug' => null,
    'status' => null,
    'mediaType' => 'tv'
])

@php
    $href = $slug ? route('anime.show', $slug) : '#';
@endphp

<div class="group relative w-full aspect-[2/3] rounded-2xl overflow-hidden cursor-pointer bg-bg-secondary">
    {{-- Year Badge --}}
    @if($year)
        <div class="absolute top-3 left-3 z-20 px-2 py-1 rounded-lg bg-black/60 backdrop-blur-md border border-white/10 shadow-lg">
            <span class="text-[10px] sm:text-xs font-bold text-white">{{ $year }}</span>
        </div>
    @endif

    {{-- Rating Badge (Rating Circle component already exists) --}}
    <div class="absolute top-3 right-3 z-20">
        <x-anime.rating-circle :rating="$rating" />
    </div>

    <a href="{{ $href }}" class="block w-full h-full relative">
        {{-- Poster Image --}}
        @if($image)
            <img src="{{ $image }}" alt="{{ $title }}" loading="lazy"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110" />
        @endif

        {{-- Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-80 group-hover:opacity-60 transition-opacity duration-500"></div>

        {{-- Hover Inset Shadow --}}
        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none shadow-[inset_0_0_80px_rgba(0,0,0,0.8)]"></div>

        {{-- Play Button Wrapper --}}
        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 z-10">
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-primary/20 backdrop-blur-sm border border-primary/50 flex items-center justify-center shadow-glow-lg group-hover:scale-100 scale-50 transition-transform duration-500">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-primary flex items-center justify-center shadow-lg text-white">
                    <x-icons.play class="w-4 h-4 sm:w-5 sm:h-5 ml-1 fill-current" />
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-5 translate-y-2 group-hover:translate-y-0 transition-transform duration-500 ease-out z-20">
            <h3 title="{{ $title }}" class="text-sm sm:text-base font-bold text-white font-rubik leading-tight mb-2 line-clamp-2 drop-shadow-lg group-hover:text-primary transition-colors duration-300">
                {{ $title }}
            </h3>

            <div class="flex items-center justify-between text-[10px] sm:text-xs text-white/70 font-medium">
                <div class="flex items-center gap-1.5 flex-wrap">
                    @if(!empty($genres))
                        @foreach(array_slice($genres, 0, 2) as $genre)
                            <span class="text-primary font-semibold">
                                {{ $genre }}{{ !$loop->last ? ',' : '' }}
                            </span>
                        @endforeach
                        @if(count($genres) > 2)
                            <span class="text-primary font-semibold">+{{ count($genres) - 2 }}</span>
                        @endif
                    @else
                        <span class="text-primary font-semibold">
                            {{ $mediaType === 'movie' ? 'Film' : 'Anime' }}
                        </span>
                    @endif
                </div>

                {{-- Watch Status Dropdown (Placeholder for now) --}}
                {{-- <div class="backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300 -mr-2">
                    <x-anime.watch-status-dropdown :anime-id="$id" variant="card" />
                </div> --}}
            </div>
        </div>

        {{-- Hover Border --}}
        <div class="absolute inset-0 border-2 border-primary/0 group-hover:border-primary/50 rounded-2xl transition-colors duration-500 pointer-events-none"></div>
    </a>
</div>
