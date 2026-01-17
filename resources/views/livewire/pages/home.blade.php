<div class="pt-24 sm:pt-28 pb-8">
    <x-layout.container class="!px-0 sm:!px-8 mb-12">
        <livewire:anime.home-hero />
    </x-layout.container>

    <x-home.latest-episodes :episodes="$latestEpisodes" />
    <x-home.recent-animes :animes="$recentAnimes" />
    <x-home.popular-movies :movies="$popularMovies" />
</div>