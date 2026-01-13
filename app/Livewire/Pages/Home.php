<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layout.app')]
class Home extends Component
{
    /** @var \Illuminate\Support\Collection */
    public $latestEpisodes;

    /** @var \Illuminate\Support\Collection */
    public $recentAnimes;

    /** @var \Illuminate\Support\Collection */
    public $popularMovies;

    public function mount(): void
    {
        $this->latestEpisodes = \App\Models\Episode::with('anime')
            ->latest()
            ->limit(10)
            ->get();

        $this->recentAnimes = \App\Models\Anime::where('media_type', 'tv')
            ->latest()
            ->limit(10)
            ->get();

        $this->popularMovies = \App\Models\Anime::where('media_type', 'movie')
            ->orderBy('vote_average', 'desc')
            ->limit(10)
            ->get();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.pages.home');
    }
}
