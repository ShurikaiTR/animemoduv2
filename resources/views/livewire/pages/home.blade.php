<?php

declare(strict_types=1);

use App\Models\Anime;
use App\Models\Episode;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('components.layout.app')]
    class extends Component {
    public function placeholder(): View
    {
        return view('livewire.pages.home-skeleton');
    }

    #[Computed(cache: true, key: 'home_featured_animes', seconds: 3600)]
    public function featuredAnimes()
    {
        return Anime::select(['id', 'title', 'slug', 'backdrop_path', 'logo_path', 'poster_path', 'overview', 'status', 'release_date', 'genres', 'vote_average', 'hero_order'])
            ->where('hero_order', '>', 0)
            ->oldest('hero_order')
            ->latest()
            ->get();
    }

    #[Computed(cache: true, key: 'home_latest_episodes', seconds: 3600)]
    public function latestEpisodes()
    {
        return Episode::select(['id', 'anime_id', 'title', 'still_path', 'season_number', 'episode_number', 'absolute_episode_number', 'created_at', 'air_date'])
            ->with(['anime:id,title,slug,poster_path,structure_type'])
            ->released()
            ->orderBy('air_date', 'desc')
            ->orderBy('season_number', 'desc')
            ->orderBy('episode_number', 'desc')
            ->limit(10)
            ->get();
    }

    #[Computed(cache: true, key: 'home_recent_animes', seconds: 3600)]
    public function recentAnimes()
    {
        return Anime::select(['id', 'title', 'slug', 'poster_path', 'release_date', 'genres', 'vote_average', 'media_type', 'updated_at'])
            ->where('media_type', 'tv')
            ->latest()
            ->limit(10)
            ->get();
    }

    #[Computed(cache: true, key: 'home_popular_movies', seconds: 3600)]
    public function popularMovies()
    {
        return Anime::select(['id', 'title', 'slug', 'poster_path', 'release_date', 'genres', 'vote_average', 'media_type'])
            ->where('media_type', 'movie')
            ->orderBy('vote_average', 'desc')
            ->limit(10)
            ->get();
    }
}; ?>

<div class="pt-20 lg:pt-24 pb-8 min-h-screen" x-data x-init="window.scrollTo(0,0)">
    <x-slot:title>Ana Sayfa - AnimeModu</x-slot:title>

    {{-- Hero Section (Loaded immediately) --}}
    <div class="mb-12 md:max-w-7xl md:mx-auto md:px-8">
        <x-home.hero-slider :featuredAnimes="$this->featuredAnimes" />
    </div>

    <x-layout.container class="!px-4 sm:!px-8 mb-12">
        <div class="space-y-12">
            {{-- Primary rows --}}
            <x-home.latest-episodes :episodes="$this->latestEpisodes" />
            <x-home.recent-animes :animes="$this->recentAnimes" />

            {{-- Defer loading for lower sections --}}
            <div wire:defer>
                <x-home.popular-movies :movies="$this->popularMovies" />
            </div>
        </div>
    </x-layout.container>
</div>