<?php

declare(strict_types=1);

namespace App\Actions\Anime;

use App\Models\Anime;
use App\Services\AnilistService;

class SyncCharactersAction
{
    public function __construct(protected AnilistService $anilist) {}

    /**
     * Sync characters for an anime from AniList.
     */
    public function execute(Anime $anime): void
    {
        // Try to find AniList ID if missing
        if (! $anime->anilist_id) {
            $results = $this->anilist->search($anime->title);
            if (! empty($results)) {
                $anime->anilist_id = $results[0]['id'];
            }
        }

        // Fetch and save characters if we have an AniList ID
        if ($anime->anilist_id) {
            $anime->characters = $this->anilist->getCharacters((int) $anime->anilist_id);
            $anime->save();
        }
    }
}
