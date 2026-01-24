<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Models\Anime;
use App\Models\Episode;
use App\Services\TmdbService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Lazy]
#[Layout('components.layout.app')]
class Watch extends Component
{
    #[Locked]
    public Anime $anime;

    #[Locked]
    public ?Episode $episode = null;

    public string $segment1;

    public ?string $segment2;

    public function mount(Anime $anime, string $segment1, ?string $segment2 = null): void
    {
        $this->anime = $anime;
        $this->segment1 = $segment1;
        $this->segment2 = $segment2;

        $this->resolveEpisode();

        $this->dispatch(
            'play-episode',
            src: $this->episode->video_url,
            poster: $this->episode->still_path
            ? TmdbService::getImageUrl($this->episode->still_path, 'original')
            : ($this->anime->backdrop_path ? TmdbService::getImageUrl($this->anime->backdrop_path, 'original') : null),
            anime_title: $this->anime->title,
            episode_title: $this->episode->season_number . '. Sezon ' . $this->episode->episode_number . '. Bölüm',
            logo: $this->anime->poster_path
            ? TmdbService::getImageUrl($this->anime->poster_path, 'w500')
            : null
        );
    }

    #[Computed]
    public function episodes()
    {
        // Cache this result for the request lifecycle
        return $this->anime->episodes()
            ->orderBy('season_number')
            ->orderBy('episode_number')
            ->get();
    }

    #[Computed]
    public function previousEpisode()
    {
        return $this->episodes->where('season_number', '<=', $this->episode->season_number)
            ->where('episode_number', '<', $this->episode->episode_number)
            ->last()
            ?? $this->episodes->where('season_number', '<', $this->episode->season_number)->last();
    }

    #[Computed]
    public function nextEpisode()
    {
        return $this->episodes->where('season_number', '>=', $this->episode->season_number)
            ->where('episode_number', '>', $this->episode->episode_number)
            ->first()
            ?? $this->episodes->where('season_number', '>', $this->episode->season_number)->first();
    }

    private function resolveEpisode(): void
    {
        $season = 1;
        $episodeNum = 1;
        $isSeasonal = false;

        if ($this->segment2) {
            if (
                preg_match('/^sezon-(\d+)$/', $this->segment1, $m1) &&
                preg_match('/^bolum-(\d+)$/', $this->segment2, $m2)
            ) {
                $season = (int) $m1[1];
                $episodeNum = (int) $m2[1];
                $isSeasonal = true;
            }
        } else {
            if (preg_match('/^bolum-(\d+)$/', $this->segment1, $m)) {
                $episodeNum = (int) $m[1];
            }
        }

        $query = $this->anime->episodes();

        if ($isSeasonal) {
            $this->episode = $query->where('season_number', $season)
                ->where('episode_number', $episodeNum)
                ->firstOrFail();
        } else {
            $this->episode = $query->where('episode_number', $episodeNum)
                ->orderBy('season_number')
                ->firstOrFail();
        }
    }


    public function getPageTitle(): string
    {
        $epTitle = $this->episode->season_number . '. Sezon ' . $this->episode->episode_number . '. Bölüm';
        return $epTitle . ' - ' . $this->anime->title . ' - ' . config('app.name');
    }
}
