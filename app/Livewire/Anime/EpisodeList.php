<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Models\Anime;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;

class EpisodeList extends Component
{
    #[Locked]
    public Anime $anime;

    #[Url(as: 'season', history: true)]
    public int $selectedSeason = 1;

    public function mount(Anime $anime): void
    {
        $this->anime = $anime;

        // Find the first available season if not provided in URL
        if (request()->query('season') === null) {
            $firstSeason = $this->anime->episodes()
                ->where('season_number', '>', 0)
                ->min('season_number');

            if ($firstSeason) {
                $this->selectedSeason = (int) $firstSeason;
            }
        }
    }

    public function selectSeason(int $season): void
    {
        $this->selectedSeason = $season;
        $this->dispatch('season-changed');
    }

    #[Computed(cache: true, key: 'anime-seasons', seconds: 3600)]
    public function seasons()
    {
        return $this->anime->episodes()
            ->where('season_number', '>', 0)
            ->select('season_number')
            ->distinct()
            ->orderBy('season_number')
            ->pluck('season_number');
    }

    #[Computed(cache: true, seconds: 3600)]
    public function episodes()
    {
        return $this->anime->episodes()
            ->where('season_number', $this->selectedSeason)
            ->orderBy('episode_number')
            ->get();
    }

    #[Computed]
    public function structureType(): string
    {
        return $this->anime->structure_type ?? 'seasonal';
    }

    public function render()
    {
        return view('livewire.anime.episode-list');
    }
}
