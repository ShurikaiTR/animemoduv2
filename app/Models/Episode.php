<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Episode extends Model
{
    use HasUuids;

    protected static function booted(): void
    {
        static::saved(fn() => Cache::forget('home_latest_episodes'));
        static::deleted(fn() => Cache::forget('home_latest_episodes'));
    }

    protected $fillable = [
        'anime_id',
        'tmdb_id',
        'title',
        'overview',
        'still_path',
        'vote_average',
        'air_date',
        'season_number',
        'episode_number',
        'absolute_episode_number',
        'duration',
        'video_url',
    ];

    protected $casts = [
        'air_date' => 'date',
    ];

    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }
}
