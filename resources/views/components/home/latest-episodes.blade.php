@props(['episodes'])

@if($episodes->isNotEmpty())
    @inject('tmdbService', 'App\Services\TmdbService')
    <section class="pb-8 pt-4 first:pt-4" aria-labelledby="latest-episodes-title">
        <x-layout.container>
            <div class="flex items-center justify-between mb-6">
                <h2 id="latest-episodes-title"
                    class="text-2xl sm:text-3xl font-bold font-rubik text-white drop-shadow-md border-l-4 border-primary pl-4">
                    Bölümler
                </h2>
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
                            ? $tmdbService->getImageUrl($episode->still_path, 'w500')
                            : ($episode->anime->poster_path
                                ? $tmdbService->getImageUrl($episode->anime->poster_path, 'w500')
                                : null);

                        $href = route('anime.show', $episode->anime->slug);
                    @endphp

                    <x-anime.episode-card :title="$episode->anime->title" :episode-number="$epNumberText" :image="$image"
                        :time-ago="$episode->created_at->diffForHumans()" :href="$href" />
                @endforeach
            </div>
        </x-layout.container>
    </section>
@endif