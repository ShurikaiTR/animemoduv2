<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Models\Anime;
use App\Models\Episode;
use App\Services\TmdbService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
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
        $this->dispatchPlayerEvent(forcePlay: false);
    }

    #[Computed]
    public function episodes(): Collection
    {
        $query = $this->anime->episodes()
            ->select(['id', 'anime_id', 'title', 'season_number', 'episode_number', 'still_path', 'air_date', 'vote_average'])
            ->orderBy('season_number')
            ->orderBy('episode_number');

        // Senaryo 1: Sezonlu Anime (Örn: Demon Slayer)
        // Sadece o anki sezonun bölümlerini getir.
        // is_seasonal flag'i veritabanında yoksa structure_type kontrol edilebilir
        if ($this->anime->is_seasonal) { // Modelde bu accessor veya column olmalı
            return $query->where('season_number', $this->episode->season_number)->get();
        }

        // Senaryo 2: Uzun Soluklu / Absolute Anime (Örn: One Piece)
        // İzlenilen bölümün etrafındaki 100 bölümü getir (50 öncesi, 50 sonrası)
        $currentEp = $this->episode->episode_number;

        return $query->whereBetween('episode_number', [
            max(1, $currentEp - 50),
            $currentEp + 50
        ])->get();
    }

    #[Computed]
    public function availableSeasons(): Collection
    {
        // Sezon listesi için distinct sorgu (Performanslı)
        return $this->anime->episodes()
            ->select('season_number')
            ->distinct()
            ->orderBy('season_number')
            ->pluck('season_number');
    }

    #[Computed]
    public function previousEpisode(): ?Episode
    {
        // Önceki bölümü bulurken de veritabanından hafif bir sorgu atabiliriz
        // veya mevcut episodes koleksiyonundan bulabiliriz (eğer aralıktaysa)

        return $this->anime->episodes()
            ->where(function ($query) {
                $query->where('season_number', '<', $this->episode->season_number)
                    ->orWhere(function ($q) {
                        $q->where('season_number', $this->episode->season_number)
                            ->where('episode_number', '<', $this->episode->episode_number);
                    });
            })
            ->orderByDesc('season_number')
            ->orderByDesc('episode_number')
            ->first();
    }

    #[Computed]
    public function nextEpisode(): ?Episode
    {
        return $this->anime->episodes()
            ->where(function ($query) {
                $query->where('season_number', '>', $this->episode->season_number)
                    ->orWhere(function ($q) {
                        $q->where('season_number', $this->episode->season_number)
                            ->where('episode_number', '>', $this->episode->episode_number);
                    });
            })
            ->orderBy('season_number')
            ->orderBy('episode_number')
            ->first();
    }

    #[Computed]
    public function pageTitle(): string
    {
        $epTitle = $this->episode->season_number . '. Sezon ' . $this->episode->episode_number . '. Bölüm';
        return $epTitle . ' - ' . $this->anime->title . ' - ' . config('app.name');
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
        } elseif (preg_match('/^bolum-(\d+)$/', $this->segment1, $m)) {
            $episodeNum = (int) $m[1];
        }

        $query = $this->anime->episodes();

        if ($isSeasonal) {
            $this->episode = $query->where('season_number', $season)
                ->where('episode_number', $episodeNum)
                ->firstOrFail();
        } else {
            // Absolute numbering fallback
            $this->episode = $query->where('episode_number', $episodeNum)
                ->orderBy('season_number')
                ->firstOrFail();
        }
    }

    private function dispatchPlayerEvent(bool $forcePlay = true): void
    {
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
            : null,
            force_play: $forcePlay
        );
    }

    public function placeholder(): View
    {
        return view('livewire.anime.partials.watch-skeleton');
    }

    public function render(): View
    {
        return view('livewire.anime.watch');
    }
}
