<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TmdbService
{
    protected string $baseUrl = 'https://api.themoviedb.org/3';

    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.key');
    }

    /**
     * Search for movies or TV shows.
     */
    public function search(string $query): array
    {
        if (! $this->apiKey || strlen($query) < 2) {
            return [];
        }

        $cacheKey = 'tmdb_search_'.md5($query);

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($query) {
            return Http::timeout(10)->get("{$this->baseUrl}/search/multi", [
                'api_key' => $this->apiKey,
                'query' => $query,
                'language' => 'tr-TR',
                'include_adult' => false,
            ])->json('results') ?? [];
        });
    }

    /**
     * Get detailed info for a movie or TV show.
     */
    public function getDetails(int $id, string $type = 'tv'): ?array
    {
        if (! $this->apiKey) {
            return null;
        }

        $cacheKey = "tmdb_{$type}_{$id}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($id, $type) {
            return Http::timeout(10)->get("{$this->baseUrl}/{$type}/{$id}", [
                'api_key' => $this->apiKey,
                'language' => 'tr-TR',
                'append_to_response' => 'credits,videos,similar',
            ])->json();
        });
    }

    /**
     * Get season details including episodes.
     */
    public function getSeasonDetails(int $tvId, int $seasonNumber): ?array
    {
        if (! $this->apiKey) {
            return null;
        }

        $cacheKey = "tmdb_season_{$tvId}_{$seasonNumber}";

        return Cache::remember($cacheKey, now()->addHour(), function () use ($tvId, $seasonNumber) {
            return Http::timeout(10)->get("{$this->baseUrl}/tv/{$tvId}/season/{$seasonNumber}", [
                'api_key' => $this->apiKey,
                'language' => 'tr-TR',
            ])->json();
        });
    }

    /**
     * Get TMDB image URL.
     */
    public function getImageUrl(?string $path, string $size = 'w500'): string
    {
        if (! $path) {
            return asset('img/placeholder.jpg');
        }

        if (str_starts_with($path, 'http') || str_starts_with($path, 'https')) {
            return $path;
        }

        if (str_starts_with($path, '/img/') || str_starts_with($path, '/icons/')) {
            return $path;
        }

        return "https://image.tmdb.org/t/p/{$size}{$path}";
    }

    /**
     * Get YouTube trailer URL.
     */
    public function getTrailerUrl(?string $key): ?string
    {
        if (! $key) {
            return null;
        }

        return "https://www.youtube.com/watch?v={$key}";
    }

    /**
     * Get year from date string.
     */
    public function getYear(?string $date): int
    {
        if (! $date) {
            return (int) date('Y');
        }

        return (int) explode('-', $date)[0];
    }
}
