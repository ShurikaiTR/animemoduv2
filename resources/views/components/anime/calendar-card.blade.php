@props(['anime'])

@php
    $posterPath = $anime->poster_path;
    $backdropPath = $anime->backdrop_path;
    $isExternal = $posterPath && str_starts_with($posterPath, 'http');

    $upcomingEpisode = $anime->episodes->first(); // Action'dan tek eleman geliyor

    // Backdrop veya Bölüm Resmi varsa öncelikli kullan
    $imageUrl = asset('images/placeholder.jpg');

    if ($upcomingEpisode && $upcomingEpisode->still_path) {
        $imageUrl = 'https://image.tmdb.org/t/p/w500' . $upcomingEpisode->still_path;
    } elseif ($backdropPath) {
        $imageUrl = $isExternal ? $backdropPath : 'https://image.tmdb.org/t/p/w500' . $backdropPath;
    } elseif ($posterPath) {
        $imageUrl = $isExternal ? $posterPath : 'https://image.tmdb.org/t/p/w500' . $posterPath;
    }

    $broadcastTime = $anime->broadcast_time ? \Carbon\Carbon::parse($anime->broadcast_time)->format('H:i') : '??';
    $isMidnight = $broadcastTime === '00:00';
    $label = $isMidnight ? '??' : $broadcastTime;

    // Genre handling - 'Animasyon' türünü anime sitesinde gereksiz olduğu için atla
    $genres = collect($anime->genres ?? [])->filter(
        fn($g) =>
        (is_string($g) && strtolower($g) !== 'animasyon' && strtolower($g) !== 'animation') ||
        ($g instanceof \App\Enums\AnimeGenre && $g !== \App\Enums\AnimeGenre::tryFrom('animasyon'))
    );

    $firstGenre = $genres->first() ?? 'Anime';

    if ($firstGenre instanceof \App\Enums\AnimeGenre) {
        $firstGenre = $firstGenre->label();
    } elseif (is_string($firstGenre) && enum_exists(\App\Enums\AnimeGenre::class)) {
        $firstGenre = \App\Enums\AnimeGenre::tryFrom(strtolower($firstGenre))?->label() ?? $firstGenre;
    }
@endphp

<div
    class="group relative flex flex-col md:flex-row items-center gap-6 bg-white/5 border border-white/5 rounded-2xl p-4 md:p-6 hover:bg-white/10 transition-all duration-300 hover:border-white/10">
    {{-- Clock / Time Section --}}
    <div
        class="shrink-0 flex flex-col items-center justify-center w-full md:w-24 h-16 md:h-24 bg-white/5 rounded-xl border border-white/5 group-hover:border-primary/30 transition-colors">
        <x-icons.clock class="w-5 h-5 text-primary mb-1" />
        <span class="text-xl font-bold text-white font-rubik tracking-wide">
            {{ $label }}
        </span>
    </div>

    {{-- Image Section --}}
    <div class="w-full md:w-40 aspect-video md:aspect-[16/9] relative rounded-xl overflow-hidden shadow-lg shrink-0">
        <img src="{{ $imageUrl }}" alt="{{ $anime->title }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent md:hidden"></div>
    </div>

    {{-- Info Section --}}
    <div class="flex-1 text-center md:text-left w-full md:w-auto">
        <div class="flex items-center justify-center md:justify-start gap-3 mb-2">
            <span
                class="text-primary text-xs font-bold uppercase tracking-wider border border-primary/20 px-2 py-0.5 rounded-md bg-primary/10">
                {{ $firstGenre }}
            </span>
        </div>
        <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-primary transition-colors line-clamp-1"
            title="{{ $anime->title }}">
            {{ $anime->title }}
        </h3>
        <p class="text-white/50 text-sm">
            @if($upcomingEpisode)
                <span class="text-white/80 font-medium">{{ $upcomingEpisode->episode_number }}. Bölüm</span>
                @if($upcomingEpisode->title && !str_contains($upcomingEpisode->title, 'Episode'))
                    • {{ $upcomingEpisode->title }}
                @endif
            @else
                {{ $anime->episodes_count + 1 }}. Bölüm
            @endif
        </p>
    </div>

    {{-- Actions Section --}}
    <div class="shrink-0 w-full md:w-auto mt-4 md:mt-0 flex gap-3 justify-center md:justify-end">
        {{-- Notification Button (Placeholder Logic) --}}
        <button
            class="h-12 w-12 flex items-center justify-center rounded-xl bg-white/5 hover:bg-white/10 text-yellow-500 transition-colors border border-white/5">
            <x-icons.bell class="w-5 h-5" />
        </button>

        <a href="{{ route('anime.show', $anime->slug) }}" wire:navigate class="flex-1 md:flex-none">
            <button
                class="w-full md:w-40 h-12 flex items-center justify-center gap-2 bg-primary hover:bg-primary-600 text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:scale-105 transition-transform text-base">
                <x-icons.play class="w-5 h-5 fill-current" /> İzle
            </button>
        </a>
    </div>

    {{-- Background Glow Effect --}}
    <div
        class="absolute -inset-1 bg-gradient-to-r from-primary/0 via-primary/5 to-primary/0 rounded-3xl opacity-0 group-hover:opacity-100 blur-xl transition-opacity duration-500 -z-10">
    </div>
</div>