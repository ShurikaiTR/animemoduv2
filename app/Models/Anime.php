<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AnimeStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Anime extends Model
{
    use HasUuids;

    protected static function booted(): void
    {
        static::saved(function (Anime $anime) {
            Cache::forget("anime_show_{$anime->slug}");
            Cache::forget('home_recent_animes');
            Cache::forget('home_popular_movies');
            Cache::forget('home_featured_animes');
        });

        static::deleted(function (Anime $anime) {
            Cache::forget("anime_show_{$anime->slug}");
            Cache::forget('home_recent_animes');
            Cache::forget('home_popular_movies');
            Cache::forget('home_featured_animes');
        });
    }

    protected $fillable = [
        'tmdb_id',
        'anilist_id',
        'title',
        'original_title',
        'overview',
        'poster_path',
        'backdrop_path',
        'logo_path',
        'vote_average',
        'release_date',
        'slug',
        'media_type',
        'structure_type',
        'status',
        'genres',
        'characters',
        'hero_order',
        'vote_count',
        'trailer_key',
    ];

    protected $casts = [
        'genres' => 'array',
        'characters' => 'array',
        'hero_order' => 'integer',
        'release_date' => 'date',
        'status' => AnimeStatus::class,
    ];

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }

    protected function isSeasonal(): Attribute
    {
        return Attribute::make(
            get: fn () => ($this->structure_type === 'seasonal' || $this->structure_type === null),
        );
    }
}
