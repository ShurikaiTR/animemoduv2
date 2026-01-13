<?php

declare(strict_types=1);

namespace App\Actions\Anime;

use App\Models\Anime;
use App\Models\Episode;
use App\Services\TmdbService;
use Illuminate\Support\Str;

class ImportAnimeAction
{
    public function __construct(
        protected TmdbService $tmdb,
        protected \App\Actions\Anime\SyncCharactersAction $syncCharacters
    ) {}

    /**
     * Import or update an anime from TMDB ID.
     */
    public function execute(int $tmdbId, string $type = 'tv', string $structureType = 'seasonal'): Anime
    {
        $details = $this->tmdb->getDetails($tmdbId, $type);

        if (! $details) {
            throw new \Exception('TMDB verisi çekilemedi.');
        }

        $title = $details['name'] ?? $details['title'];

        $anime = Anime::updateOrCreate(
            ['tmdb_id' => $tmdbId],
            [
                'title' => $title,
                'original_title' => $details['original_name'] ?? $details['original_title'],
                'overview' => $details['overview'],
                'poster_path' => $details['poster_path'],
                'backdrop_path' => $details['backdrop_path'],
                'vote_average' => $details['vote_average'],
                'vote_count' => $details['vote_count'],
                'release_date' => $details['first_air_date'] ?? $details['release_date'],
                'media_type' => $type === 'tv' ? 'tv' : 'movie',
                'structure_type' => $structureType,
                'status' => $this->mapStatus($details['status'] ?? ''),
                'slug' => Str::slug($title),
                'genres' => collect($details['genres'])->pluck('name')->toArray(),
                'trailer_key' => $this->getTrailerKey($details),
            ]
        );

        // Sync characters from AniList
        try {
            $this->syncCharacters->execute($anime);
        } catch (\Exception $e) {
            // Log or ignore AniList errors to not break TMDB import
        }

        // If it's a TV show, import episodes for the first season by default
        if ($type === 'tv' && isset($details['seasons'])) {
            $this->importEpisodes($anime, $details['seasons']);
        }

        return $anime;
    }

    /**
     * Import episodes for all seasons.
     */
    protected function importEpisodes(Anime $anime, array $seasons): void
    {
        $absoluteNumber = 1;
        $now = now();

        foreach ($seasons as $season) {
            // We usually only care about regular seasons (season_number > 0)
            if ($season['season_number'] === 0) {
                continue;
            }

            $seasonDetails = $this->tmdb->getSeasonDetails((int) $anime->tmdb_id, $season['season_number']);

            if (! $seasonDetails || ! isset($seasonDetails['episodes'])) {
                continue;
            }

            foreach ($seasonDetails['episodes'] as $ep) {
                // Only import episodes that have already aired
                if (isset($ep['air_date']) && now()->parse($ep['air_date'])->isAfter($now)) {
                    continue;
                }

                Episode::updateOrCreate(
                    [
                        'anime_id' => $anime->id,
                        'season_number' => $ep['season_number'],
                        'episode_number' => $ep['episode_number'],
                    ],
                    [
                        'tmdb_id' => $ep['id'],
                        'title' => $ep['name'],
                        'overview' => $ep['overview'],
                        'still_path' => $ep['still_path'],
                        'vote_average' => $ep['vote_average'],
                        'air_date' => $ep['air_date'],
                        'absolute_episode_number' => $absoluteNumber++,
                        'duration' => $ep['runtime'] ?? null,
                    ]
                );
            }
        }
    }

    /**
     * Extract trailer key from details.
     */
    protected function getTrailerKey(array $details): ?string
    {
        if (! isset($details['videos']['results'])) {
            return null;
        }

        $trailer = collect($details['videos']['results'])->firstWhere('type', 'Trailer');

        return $trailer ? $trailer['key'] : null;
    }

    /**
     * Map TMDB status to Turkish status.
     */
    protected function mapStatus(string $status): string
    {
        return match ($status) {
            'Returning Series', 'In Production', 'Devam Eden Dizi', 'Planlanıyor' => 'Devam Ediyor',
            'Ended', 'Released', 'Canceled', 'Cancelled', 'Bitti', 'Yayındaydı' => 'Tamamlandı',
            default => 'Tamamlandı',
        };
    }
}
