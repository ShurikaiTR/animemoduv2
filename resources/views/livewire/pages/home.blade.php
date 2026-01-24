<div class="pt-20 lg:pt-24 pb-8 min-h-screen" x-data x-init="window.scrollTo(0,0)">
    <x-slot:title>Ana Sayfa - AnimeModu</x-slot:title>
    {{-- Hero Section (Full width on mobile, constrained on desktop) --}}
    <div class="mb-12 md:max-w-7xl md:mx-auto md:px-8">
        <x-home.hero-slider :featuredAnimes="$featuredAnimes" />
    </div>

    <x-layout.container class="!px-4 sm:!px-8 mb-12">
        <div class="space-y-12">
            <x-home.latest-episodes :episodes="$latestEpisodes" />
            <x-home.recent-animes :animes="$recentAnimes" />
            <x-home.popular-movies :movies="$popularMovies" />
        </div>
    </x-layout.container>
</div>