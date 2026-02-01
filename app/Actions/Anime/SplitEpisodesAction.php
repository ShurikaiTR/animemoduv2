<?php

declare(strict_types=1);

namespace App\Actions\Anime;

use App\Models\Anime;
use App\Models\Episode;
use App\Services\AnilistService;
use Illuminate\Support\Facades\DB;

class SplitEpisodesAction
{
    public function __construct(
        protected AnilistService $anilist
    ) {
    }

    /**
     * AniList'ten sezon bilgilerini çekip bölümleri otomatik böl.
     *
     * @return array{updated: int, seasons: array}
     */
    public function execute(Anime $anime): array
    {
        if (!$anime->anilist_id) {
            throw new \Exception('Bu animenin AniList ID\'si yok.');
        }

        $seasonChain = $this->anilist->getSeasonChain($anime->anilist_id);

        if (empty($seasonChain)) {
            throw new \Exception('AniList\'te sezon bilgisi bulunamadı.');
        }

        $updatedCount = 0;
        $absoluteStart = 1;

        DB::transaction(function () use ($anime, $seasonChain, &$updatedCount, &$absoluteStart) {
            foreach ($seasonChain as $index => $season) {
                $seasonNumber = $index + 1;
                $episodeCount = $season['episodes'];
                $absoluteEnd = $absoluteStart + $episodeCount - 1;

                $updatedCount += Episode::where('anime_id', $anime->id)
                    ->whereBetween('absolute_episode_number', [$absoluteStart, $absoluteEnd])
                    ->update([
                        'season_number' => $seasonNumber,
                        'episode_number' => DB::raw("absolute_episode_number - " . ($absoluteStart - 1)),
                    ]);

                $absoluteStart = $absoluteEnd + 1;
            }
        });

        return [
            'updated' => $updatedCount,
            'seasons' => $seasonChain,
        ];
    }
}
