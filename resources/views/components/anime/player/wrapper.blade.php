@props(['anime', 'episode'])

@inject('tmdbService', 'App\Services\TmdbService')

<div class="flex flex-col gap-4">
    {{-- Video Player Container --}}
    @persist('player-wrapper')
    <div x-data="{ 
            isPlaying: false,
            animeTitle: '{{ $anime->title }}',
            episodeTitle: '{{ $episode->season_number }}. Sezon {{ $episode->episode_number }}. Bölüm',
            poster: '{{ $episode->still_path ? $tmdbService->getImageUrl($episode->still_path, 'original') : ($anime->backdrop_path ? $tmdbService->getImageUrl($anime->backdrop_path, 'original') : null) }}',
            logo: '{{ $anime->poster_path ? $tmdbService->getImageUrl($anime->poster_path, 'w500') : null }}'
        }" x-on:play-episode.window="
            isPlaying = true;
            animeTitle = $event.detail.anime_title;
            episodeTitle = $event.detail.episode_title;
            poster = $event.detail.poster;
            logo = $event.detail.logo;
        "
        class="w-full aspect-video bg-black rounded-xl overflow-hidden shadow-2xl border border-white/5 ring-1 ring-white/10 relative z-10">
        
        {{-- Real Player --}}
        <template x-if="isPlaying">
            <x-anime.player.nuevo 
                :src="$episode->video_url"
                :poster="$episode->still_path ? $tmdbService->getImageUrl($episode->still_path, 'original') : ($anime->backdrop_path ? $tmdbService->getImageUrl($anime->backdrop_path, 'original') : null)"
                :anime="$anime" 
                :episode="$episode" 
                :logo="$anime->poster_path ? $tmdbService->getImageUrl($anime->poster_path, 'w500') : null" 
            />
        </template>

        {{-- Fake Player (Cover) --}}
        <div x-show="!isPlaying" class="w-full h-full">
            <x-anime.player.fake 
                @click="isPlaying = true"
                :poster="$episode->still_path ? $tmdbService->getImageUrl($episode->still_path, 'original') : ($anime->backdrop_path ? $tmdbService->getImageUrl($anime->backdrop_path, 'original') : null)"
                :anime-title="$anime->title"
                :episode-title="$episode->season_number . '. Sezon ' . $episode->episode_number . '. Bölüm'"
                :logo="$anime->poster_path ? $tmdbService->getImageUrl($anime->poster_path, 'w500') : null"
            />
        </div>
    </div>
    @endpersist
</div>
