<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Models\Anime;
use Livewire\Component;

class EpisodeList extends Component
{
    public Anime $anime;

    public int $selectedSeason = 1;

    public function mount(Anime $anime): void
    {
        $this->anime = $anime;

        // Find the first available season
        $firstSeason = $this->anime->episodes()
            ->where('season_number', '>', 0)
            ->min('season_number');

        if ($firstSeason) {
            $this->selectedSeason = $firstSeason;
        }
    }

    public function selectSeason(int $season): void
    {
        $this->selectedSeason = $season;
    }

    public function render()
    {
        $allSeasons = $this->anime->episodes()
            ->where('season_number', '>', 0)
            ->select('season_number')
            ->distinct()
            ->orderBy('season_number')
            ->pluck('season_number');

        $episodes = $this->anime->episodes()
            ->where('season_number', $this->selectedSeason)
            ->orderBy('episode_number')
            ->get();

        return view('livewire.anime.episode-list', [
            'seasons' => $allSeasons,
            'episodes' => $episodes,
            'structureType' => $this->anime->structure_type ?? 'seasonal',
        ]);
    }
}
