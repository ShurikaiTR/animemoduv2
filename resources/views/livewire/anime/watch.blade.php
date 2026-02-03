@use('App\Services\TmdbService')

<div>
    <x-slot:title>
        {{ $this->pageTitle }}
    </x-slot:title>
    {{-- Hero Section --}}
    <x-anime.watch-hero :anime="$this->anime" />

    {{-- Main Content --}}
    <div class="relative z-20 pt-24 pb-16">
        <x-layout.container>
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_22rem] gap-8 items-start">

                {{-- Left Column: Player & Controls --}}
                <div class="flex flex-col gap-8 min-w-0">
                    <div class="flex flex-col gap-4">
                        {{-- Video Player Container --}}
                        {{-- Video Player Container --}}
                        <x-anime.player.wrapper :anime="$anime" :episode="$episode" />

                        {{-- Controls Bar --}}
                        @php
                            $prev = $this->previousEpisode;
                            $next = $this->nextEpisode;

                            $prevUrl = $prev
                                ? ($this->anime->is_seasonal
                                    ? route('anime.watch', ['anime' => $this->anime->slug, 'segment1' => "sezon-{$prev->season_number}", 'segment2' => "bolum-{$prev->episode_number}"])
                                    : route('anime.watch', ['anime' => $this->anime->slug, 'segment1' => "bolum-{$prev->episode_number}"]))
                                : null;

                            $nextUrl = $next
                                ? ($this->anime->is_seasonal
                                    ? route('anime.watch', ['anime' => $this->anime->slug, 'segment1' => "sezon-{$next->season_number}", 'segment2' => "bolum-{$next->episode_number}"])
                                    : route('anime.watch', ['anime' => $this->anime->slug, 'segment1' => "bolum-{$next->episode_number}"]))
                                : null;
                        @endphp

                        <div
                            class="flex items-center justify-between p-3 rounded-xl bg-bg-secondary/80 backdrop-blur-xl border border-white/5 shadow-xl relative z-20">
                            {{-- Navigation Group --}}
                            <div class="flex items-center gap-2">
                                {{-- Previous Button --}}
                                @if($prevUrl)
                                    <a href="{{ $prevUrl }}" wire:navigate class="group">
                                        <button
                                            class="flex items-center bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/10 text-white hover:text-primary rounded-xl h-10 px-4 sm:px-5 transition-all duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="mr-2 group-hover:-translate-x-1 transition-transform">
                                                <polyline points="15 18 9 12 15 6" />
                                            </svg>
                                            <span class="hidden sm:inline font-medium">Önceki Bölüm</span>
                                        </button>
                                    </a>
                                @else
                                    <button disabled
                                        class="flex items-center bg-white/5 border border-white/5 text-white/10 rounded-xl h-10 px-4 sm:px-5 cursor-not-allowed">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="mr-2">
                                            <polyline points="15 18 9 12 15 6" />
                                        </svg>
                                        <span class="hidden sm:inline font-medium">Önceki Bölüm</span>
                                    </button>
                                @endif

                                {{-- Next Button --}}
                                @if($nextUrl)
                                    <a href="{{ $nextUrl }}" wire:navigate class="group">
                                        <button
                                            class="flex items-center bg-primary hover:bg-primary-hover text-white hover:scale-[1.02] border-none rounded-xl h-10 px-4 sm:px-6 transition-all duration-300 shadow-[0_0_20px_-5px_rgba(47,128,237,0.4)]">
                                            <span class="hidden sm:inline font-bold">Sonraki Bölüm</span>
                                            <span class="sm:hidden font-bold">İleri</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="ml-2 group-hover:translate-x-1 transition-transform">
                                                <polyline points="9 18 15 12 9 6" />
                                            </svg>
                                        </button>
                                    </a>
                                @else
                                    <button disabled
                                        class="flex items-center bg-white/5 border border-white/5 text-white/10 rounded-xl h-10 px-4 sm:px-6 cursor-not-allowed">
                                        <span class="hidden sm:inline font-medium">Sonraki Bölüm</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="ml-2">
                                            <polyline points="9 18 15 12 9 6" />
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            {{-- Tools Group --}}
                            <div class="flex items-center gap-2">
                                <button
                                    class="flex items-center gap-2 text-white/60 hover:text-primary hover:bg-primary/10 rounded-xl h-10 px-4 transition-all duration-300"
                                    title="Paylaş">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="18" cy="5" r="3" />
                                        <circle cx="6" cy="12" r="3" />
                                        <circle cx="18" cy="19" r="3" />
                                        <line x1="8.59" x2="15.42" y1="13.51" y2="17.49" />
                                        <line x1="15.41" x2="8.59" y1="6.51" y2="10.49" />
                                    </svg>
                                    <span class="hidden sm:inline font-medium">Paylaş</span>
                                </button>
                                <button
                                    class="flex items-center gap-2 text-white/60 hover:text-red-500 hover:bg-red-500/10 rounded-xl h-10 px-4 transition-all duration-300"
                                    title="Bildir">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z" />
                                        <line x1="4" x2="4" y1="22" y2="15" />
                                    </svg>
                                    <span class="hidden sm:inline font-medium">Bildir</span>
                                </button>
                            </div>
                        </div>


                        {{-- Mobile Sidebar (Displayed below video on small screens) --}}
                        <div class="xl:hidden mt-8">
                            <x-anime.watch-sidebar :anime="$this->anime" :episodes="$this->episodes"
                                :current-episode="$this->episode" :available-seasons="$this->availableSeasons" />
                        </div>

                    </div>

                    {{-- Comments Section --}}
                    <livewire:anime.comments :anime="$this->anime" :episode="$this->episode" lazy />
                </div>

                {{-- Desktop Sidebar (Right column) --}}
                <x-anime.watch-sidebar :anime="$this->anime" :episodes="$this->episodes"
                    :current-episode="$this->episode" :available-seasons="$this->availableSeasons"
                    class="hidden xl:block" />

            </div>
        </x-layout.container>
    </div>
</div>