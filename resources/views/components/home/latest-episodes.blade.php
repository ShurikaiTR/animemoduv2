@props(['episodes'])

@if($episodes->isNotEmpty())
    @inject('tmdbService', 'App\Services\TmdbService')
    <section class="pb-8 pt-4 first:pt-4" aria-labelledby="latest-episodes-title">
        <x-layout.container>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 id="latest-episodes-title" class="text-2xl font-bold text-white font-rubik">
                        Son Eklenen Bölümler
                    </h2>
                    <p class="text-text-main/60 text-sm mt-1">En yeni bölümleri kaçırma</p>
                </div>
                <a href="{{ route('anime.hub') }}"
                    class="text-sm font-medium text-primary hover:text-white transition-colors">
                    Tümünü Gör
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5 gap-4 sm:gap-6">
                @foreach($episodes as $episode)
                    @php
                        $isSeasonal = $episode->anime->structure_type === 'seasonal';
                        $epNumberText = $isSeasonal
                            ? "{$episode->season_number}.Sezon {$episode->episode_number}.Bölüm"
                            : "{$episode->absolute_episode_number}. Bölüm";

                        // Fallback image logic using TmdbService
                        $image = $episode->still_path
                            ? $tmdbService->getImageUrl($episode->still_path, 'w342')
                            : ($episode->anime->poster_path
                                ? $tmdbService->getImageUrl($episode->anime->poster_path, 'w342')
                                : null);

                        $href = $isSeasonal
                            ? route('anime.watch', ['anime' => $episode->anime->slug, 'segment1' => "sezon-{$episode->season_number}", 'segment2' => "bolum-{$episode->episode_number}"])
                            : route('anime.watch', ['anime' => $episode->anime->slug, 'segment1' => "bolum-{$episode->absolute_episode_number}"]);
                    @endphp

                    <x-anime.episode-card :title="$episode->anime->title" :episode-number="$epNumberText" :image="$image"
                        :time-ago="$episode->created_at->diffForHumans()" :href="$href" :attr="$loop->first ? 'fetchpriority=high' : ''" />
                @endforeach
            </div>
        </x-layout.container>
    </section>
@endif