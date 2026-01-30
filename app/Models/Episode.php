<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Episode extends Model
{
    use HasUuids;

    protected static function booted(): void
    {
        static::saved(function ($episode) {
            Cache::forget('home_latest_episodes');
            Cache::forget("anime_seasons_{$episode->anime_id}");
            Cache::forget("anime_episodes_{$episode->anime_id}_{$episode->season_number}");
            Cache::forget('weekly_schedule');
        });

        static::deleted(function ($episode) {
            Cache::forget('home_latest_episodes');
            Cache::forget("anime_seasons_{$episode->anime_id}");
            Cache::forget("anime_episodes_{$episode->anime_id}_{$episode->season_number}");
            Cache::forget('weekly_schedule');
        });
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

    /**
     * Scope: Yayınlanmış bölümler.
     */
    public function scopeReleased(Builder $query): void
    {
        $query->where('air_date', '<=', now());
    }

    /**
     * Scope: Yayınlanmış ancak videosu henüz eklenmemiş bölümler.
     */
    public function scopeNeedingVideo(Builder $query): void
    {
        $query->released()->whereNull('video_url');
    }

    /**
     * Scope: Gelecekteki (beklenen) bölümler.
     */
    public function scopeUpcoming(Builder $query): void
    {
        $query->where('air_date', '>', now());
    }

    /**
     * Attribute: Bölüm yayınlandı mı?
     */
    protected function isReleased(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->air_date && $this->air_date <= now(),
        );
    }
}
