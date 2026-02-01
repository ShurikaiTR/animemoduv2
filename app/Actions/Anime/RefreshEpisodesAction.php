<?php

declare(strict_types=1);

namespace App\Actions\Anime;

use App\Models\Anime;
use App\Models\Episode;
use App\Services\TmdbService;

class RefreshEpisodesAction
{
    public function __construct(
        protected TmdbService $tmdb
    ) {
    }

    /**
     * TMDB'den sadece bölüm bilgilerini (resim, özet) güncelle.
     */
    public function execute(Anime $anime): int
    {
        $updatedCount = 0;

        $details = $this->tmdb->getDetails((int) $anime->tmdb_id, $anime->media_type);

        if (!$details || !isset($details['seasons'])) {
            return 0;
        }

        foreach ($details['seasons'] as $season) {
            if ($season['season_number'] === 0) {
                continue;
            }

            $seasonDetails = $this->tmdb->getSeasonDetails((int) $anime->tmdb_id, $season['season_number']);

            if (!$seasonDetails || !isset($seasonDetails['episodes'])) {
                continue;
            }

            foreach ($seasonDetails['episodes'] as $ep) {
                $updated = Episode::where('anime_id', $anime->id)
                    ->where('season_number', $ep['season_number'])
                    ->where('episode_number', $ep['episode_number'])
                    ->update([
                        'title' => $ep['name'],
                        'overview' => $ep['overview'],
                        'still_path' => $ep['still_path'],
                        'vote_average' => $ep['vote_average'],
                        'duration' => $ep['runtime'] ?? null,
                    ]);

                $updatedCount += $updated;
            }
        }

        // Clear homepage cache so changes appear immediately
        \Illuminate\Support\Facades\Cache::forget('home_latest_episodes');

        return $updatedCount;
    }
}
