@props(['animes'])

@if($animes->isNotEmpty())
    <section class="pb-12 pt-4">
        <x-layout.container>
            <div class="flex items-center justify-between mb-8">
                <h2
                    class="text-2xl sm:text-3xl font-bold font-rubik text-white drop-shadow-md border-l-4 border-primary pl-4">
                    Seriler
                </h2>
                <a href="/kesfet"
                    class="flex items-center gap-2 text-sm font-bold text-primary hover:text-white transition-colors group">
                    Tümünü Gör
                    <x-heroicon-o-arrow-right class="w-4 h-4 transition-transform group-hover:translate-x-1" />
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 sm:gap-8">
                @foreach($animes as $anime)
                    <x-anime.anime-card :id="$anime->id" :title="$anime->title" :year="$anime->release_date?->format('Y')"
                        :genres="$anime->genres" :rating="$anime->vote_average" :image="$anime->poster_path ? 'https://image.tmdb.org/t/p/w500' . $anime->poster_path : null" :slug="$anime->slug"
                        :media-type="$anime->media_type" />
                @endforeach
            </div>
        </x-layout.container>
    </section>
@endif