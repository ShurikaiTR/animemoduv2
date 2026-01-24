<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Anime;
use App\Models\Episode;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
#[Layout('components.layout.app')]
#[\Livewire\Attributes\Title('Ana Sayfa - AnimeModu')]
class Home extends Component
{
    /** @var \Illuminate\Support\Collection */
    public $latestEpisodes;

    /** @var \Illuminate\Support\Collection */
    public $recentAnimes;

    /** @var \Illuminate\Support\Collection */
    public $popularMovies;

    /** @var array */
    public $featuredAnimes = [];

    public function placeholder(): View
    {
        return view('livewire.pages.home-skeleton');
    }

    public function mount(): void
    {
        $this->latestEpisodes = Cache::remember('home_latest_episodes', 3600, function () {
            return Episode::select(['id', 'anime_id', 'title', 'still_path', 'season_number', 'episode_number', 'absolute_episode_number', 'created_at'])
                ->with(['anime:id,title,slug,poster_path,structure_type'])
                ->orderBy('created_at', 'desc')
                ->orderBy('season_number', 'desc')
                ->orderBy('episode_number', 'desc')
                ->limit(10)
                ->get();
        });

        $this->recentAnimes = Cache::remember('home_recent_animes', 3600, function () {
            return Anime::select(['id', 'title', 'slug', 'poster_path', 'release_date', 'genres', 'vote_average', 'media_type'])
                ->where('media_type', 'tv')
                ->latest()
                ->limit(10)
                ->get();
        });

        $this->popularMovies = Cache::remember('home_popular_movies', 3600, function () {
            return Anime::select(['id', 'title', 'slug', 'poster_path', 'release_date', 'genres', 'vote_average', 'media_type'])
                ->where('media_type', 'movie')
                ->orderBy('vote_average', 'desc')
                ->limit(10)
                ->get();
        });

        $this->featuredAnimes = Cache::remember('home_featured_animes', 3600, function () {
            return Anime::select(['id', 'title', 'slug', 'backdrop_path', 'logo_path', 'poster_path', 'overview', 'status', 'release_date', 'genres', 'vote_average', 'hero_order'])
                ->where('hero_order', '>', 0)
                ->oldest('hero_order')
                ->latest()
                ->get();
        });
    }

}
