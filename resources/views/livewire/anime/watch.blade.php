<div>
    {{-- Hero Section --}}
    <x-anime.watch-hero :anime="$anime" />

    {{-- Main Content --}}
    <div class="relative z-20 pt-24 pb-16">
    <x-layout.container>
             <div class="grid grid-cols-1 xl:grid-cols-[1fr_22rem] gap-8 items-start">
                
                {{-- Left Column: Player & Controls --}}
                <div class="flex flex-col gap-8 min-w-0">
                    <div class="flex flex-col gap-4">
                        {{-- Video Player Container --}}
                        @persist('player-wrapper')
                        <div 
                            x-data="{ 
                                isPlaying: false,
                                animeTitle: '{{ $anime->title }}',
                                episodeTitle: '{{ $episode->season_number }}. Sezon {{ $episode->episode_number }}. Bölüm',
                                poster: '{{ $episode->still_path ? \App\Services\TmdbService::getImageUrl($episode->still_path, 'original') : ($anime->backdrop_path ? \App\Services\TmdbService::getImageUrl($anime->backdrop_path, 'original') : null) }}',
                                logo: '{{ $anime->poster_path ? \App\Services\TmdbService::getImageUrl($anime->poster_path, 'w500') : null }}'
                            }"
                            x-on:play-episode.window="
                                animeTitle = $event.detail.anime_title;
                                episodeTitle = $event.detail.episode_title;
                                poster = $event.detail.poster;
                                logo = $event.detail.logo;
                            "
                            class="w-full aspect-video bg-black rounded-xl overflow-hidden shadow-2xl border border-white/5 ring-1 ring-white/10 relative z-10"
                        >
                            {{-- Real Player --}}
                            <template x-if="isPlaying">
                                <x-anime.video-player-custom
                                    :src="$episode->video_url" 
                                    :poster="$episode->still_path ? \App\Services\TmdbService::getImageUrl($episode->still_path, 'original') : ($anime->backdrop_path ? \App\Services\TmdbService::getImageUrl($anime->backdrop_path, 'original') : null)"
                                    :anime="$anime"
                                    :episode="$episode"
                                    :logo="$anime->poster_path ? \App\Services\TmdbService::getImageUrl($anime->poster_path, 'w500') : null"
                                />
                            </template>

                            {{-- Fake Player (Cover) --}}
                            <div x-show="!isPlaying" class="w-full h-full">
                                <button
                                    type="button"
                                    @click="isPlaying = true"
                                    class="w-full h-full relative bg-black flex items-center justify-center group cursor-pointer overflow-hidden select-none outline-none focus-visible:ring-2 focus-visible:ring-primary z-10"
                                    :aria-label="animeTitle + ' ' + episodeTitle + ' Bölümünü Başlat'"
                                >
                                    {{-- Header Overlay --}}
                                    <div class="absolute inset-0 z-20 pointer-events-none">
                                        <div class="absolute top-6 left-6 z-40 flex items-center gap-4 animate-in fade-in slide-in-from-top-4 duration-700">
                                            <template x-if="logo">
                                                <div class="relative">
                                                    <div class="absolute inset-0 rounded-full bg-primary/40 animate-[ping_3s_cubic-bezier(0,0,0.2,1)_infinite] opacity-50"></div>
                                                    <div class="relative w-14 h-14 rounded-full overflow-hidden border-2 border-white/10 shadow-xl bg-black/50 backdrop-blur-md z-10">
                                                        <img :src="logo" :alt="animeTitle" class="w-full h-full object-cover">
                                                    </div>
                                                </div>
                                            </template>
                                            <div class="flex flex-col gap-1 drop-shadow-md text-left">
                                                <h3 x-text="animeTitle" class="text-white font-bold text-xl leading-none tracking-wide font-rubik text-shadow-lg"></h3>
                                                <p x-text="episodeTitle" class="text-white/90 text-sm font-medium tracking-wide text-shadow-md"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <template x-if="poster">
                                        {{-- Poster Image --}}
                                        <div class="absolute inset-0 w-full h-full">
                                            <img
                                                :src="poster"
                                                :alt="animeTitle + ' ' + episodeTitle + ' video kapağı'"
                                                class="absolute inset-0 w-full h-full object-cover opacity-30 group-hover:opacity-40 transition-all duration-700 scale-105 group-hover:scale-100 blur-sm group-hover:blur-0"
                                            />
                                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-black/40"></div>
                                        </div>
                                    </template>

                                    {{-- Play Button Container --}}
                                    <div class="relative z-20">
                                        {{-- Pulse Rings --}}
                                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 rounded-full bg-primary/40 animate-[ping_2s_cubic-bezier(0,0,0.2,1)_infinite] opacity-75"></div>
                                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 rounded-full bg-primary/20 animate-[ping_2s_cubic-bezier(0,0,0.2,1)_infinite] delay-300 opacity-50"></div>

                                        {{-- Main Button --}}
                                        <div class="relative w-24 h-24 rounded-full bg-white/5 backdrop-blur-md border border-white/10 flex items-center justify-center transition-all duration-500 group-hover:scale-110 group-hover:bg-primary group-hover:border-primary shadow-[0_0_40px_-5px_rgba(0,0,0,0.5)] group-hover:shadow-[0_0_40px_-5px_rgba(var(--primary-rgb),0.5)] animate-[pulse_3s_ease-in-out_infinite]">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10 text-white ml-1.5 transition-colors duration-300">
                                                <path fill-rule="evenodd" d="M4.5 5.653c0-1.426 1.529-2.33 2.779-1.643l11.54 6.348c1.295.712 1.295 2.573 0 3.285L7.28 19.991c-1.25.687-2.779-.217-2.779-1.643V5.653z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>

                                    {{-- Bottom Text --}}
                                    <div class="absolute bottom-12 left-0 right-0 text-center z-20 opacity-0 transform translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-500 delay-100">
                                        <span class="text-white font-medium text-lg tracking-wide drop-shadow-lg">
                                            Bölümü Başlat
                                        </span>
                                    </div>
                                </button>
                            </div>
                        </div>
                        @endpersist

                        {{-- Controls Bar --}}
                         @php
                            $prev = $this->previousEpisode;
                            $next = $this->nextEpisode;

                            $prevUrl = $prev 
                                ? ($anime->is_seasonal 
                                    ? route('anime.watch', ['anime' => $anime->slug, 'segment1' => "sezon-{$prev->season_number}", 'segment2' => "bolum-{$prev->episode_number}"])
                                    : route('anime.watch', ['anime' => $anime->slug, 'segment1' => "bolum-{$prev->episode_number}"]))
                                : null;
                                
                            $nextUrl = $next 
                                ? ($anime->is_seasonal 
                                    ? route('anime.watch', ['anime' => $anime->slug, 'segment1' => "sezon-{$next->season_number}", 'segment2' => "bolum-{$next->episode_number}"])
                                    : route('anime.watch', ['anime' => $anime->slug, 'segment1' => "bolum-{$next->episode_number}"]))
                                : null;
                        @endphp
                        
                        <div class="flex items-center justify-between p-3 rounded-xl bg-bg-secondary/80 backdrop-blur-xl border border-white/5 shadow-xl relative z-20">
                            {{-- Navigation Group --}}
                            <div class="flex items-center gap-2">
                                {{-- Previous Button --}}
                                @if($prevUrl)
                                    <a href="{{ $prevUrl }}" wire:navigate class="group">
                                        <button class="flex items-center bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/10 text-white hover:text-primary rounded-xl h-10 px-4 sm:px-5 transition-all duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 group-hover:-translate-x-1 transition-transform"><polyline points="15 18 9 12 15 6"/></svg>
                                            <span class="hidden sm:inline font-medium">Önceki Bölüm</span>
                                        </button>
                                    </a>
                                @else
                                    <button disabled class="flex items-center bg-white/5 border border-white/5 text-white/10 rounded-xl h-10 px-4 sm:px-5 cursor-not-allowed">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><polyline points="15 18 9 12 15 6"/></svg>
                                        <span class="hidden sm:inline font-medium">Önceki Bölüm</span>
                                    </button>
                                @endif

                                {{-- Next Button --}}
                                @if($nextUrl)
                                    <a href="{{ $nextUrl }}" wire:navigate class="group">
                                        <button class="flex items-center bg-primary hover:bg-primary-hover text-white hover:scale-[1.02] border-none rounded-xl h-10 px-4 sm:px-6 transition-all duration-300 shadow-[0_0_20px_-5px_rgba(47,128,237,0.4)]">
                                            <span class="hidden sm:inline font-bold">Sonraki Bölüm</span>
                                            <span class="sm:hidden font-bold">İleri</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 group-hover:translate-x-1 transition-transform"><polyline points="9 18 15 12 9 6"/></svg>
                                        </button>
                                    </a>
                                @else
                                    <button disabled class="flex items-center bg-white/5 border border-white/5 text-white/10 rounded-xl h-10 px-4 sm:px-6 cursor-not-allowed">
                                        <span class="hidden sm:inline font-medium">Sonraki Bölüm</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2"><polyline points="9 18 15 12 9 6"/></svg>
                                    </button>
                                @endif
                            </div>

                            {{-- Tools Group --}}
                            <div class="flex items-center gap-2">
                                <button class="flex items-center gap-2 text-white/60 hover:text-primary hover:bg-primary/10 rounded-xl h-10 px-4 transition-all duration-300" title="Paylaş">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" x2="15.42" y1="13.51" y2="17.49"/><line x1="15.41" x2="8.59" y1="6.51" y2="10.49"/></svg>
                                    <span class="hidden sm:inline font-medium">Paylaş</span>
                                </button>
                                <button class="flex items-center gap-2 text-white/60 hover:text-red-500 hover:bg-red-500/10 rounded-xl h-10 px-4 transition-all duration-300" title="Bildir">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" x2="4" y1="22" y2="15"/></svg>
                                    <span class="hidden sm:inline font-medium">Bildir</span>
                                </button>
                            </div>
                        </div>


                        {{-- Mobile Sidebar (Displayed below video on small screens) --}}
                        <div class="xl:hidden mt-8">
                             <x-anime.watch-sidebar 
                                :anime="$anime" 
                                :episodes="$this->episodes" 
                                :current-episode="$episode" 
                            />
                        </div>

                    </div>

                    {{-- Comments Section (Placeholder) --}}
                    {{-- <livewire:anime.comments :anime="$anime" :episode="$episode" /> --}}
                </div>

                {{-- Desktop Sidebar (Right column) --}}
                <x-anime.watch-sidebar 
                    :anime="$anime" 
                    :episodes="$this->episodes" 
                    :current-episode="$episode"
                    class="hidden xl:block"
                />

            </div>
        </x-layout.container>
    </div>
</div>
