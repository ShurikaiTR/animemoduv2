<div class="pt-20 lg:pt-24 pb-8">
    <x-layout.container class="!px-4 sm:!px-8 mb-12">
        <x-home.hero-slider :featuredAnimes="$featuredAnimes" class="mb-12 rounded-3xl overflow-hidden shadow-2xl" />

        <div class="mt-12 space-y-12">
            <x-home.latest-episodes :episodes="$latestEpisodes" />
            <x-home.recent-animes :animes="$recentAnimes" />
            <x-home.popular-movies :movies="$popularMovies" />
        </div>
    </x-layout.container>
</div>