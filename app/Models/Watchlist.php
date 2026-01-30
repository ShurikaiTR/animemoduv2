<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\WatchStatus;

class Watchlist extends Model
{
    protected $fillable = [
        'user_id',
        'anime_id',
        'status',
        'progress',
        'score',
    ];

    protected $casts = [
        'status' => WatchStatus::class,
        'progress' => 'integer',
        'score' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function anime(): BelongsTo
    {
        return $this->belongsTo(Anime::class);
    }
}
