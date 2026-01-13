<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anime extends Model
{
    use HasUuids;

    protected $fillable = [
        'tmdb_id',
        'anilist_id',
        'title',
        'original_title',
        'overview',
        'poster_path',
        'backdrop_path',
        'vote_average',
        'release_date',
        'slug',
        'media_type',
        'structure_type',
        'status',
        'genres',
        'characters',
        'is_featured',
        'vote_count',
        'trailer_key',
    ];

    protected $casts = [
        'genres' => 'array',
        'characters' => 'array',
        'is_featured' => 'boolean',
        'release_date' => 'date',
        'status' => \App\Enums\AnimeStatus::class,
    ];

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }
}
