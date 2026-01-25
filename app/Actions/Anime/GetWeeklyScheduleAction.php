<?php

declare(strict_types=1);

namespace App\Actions\Anime;

use App\Models\Anime;
use App\Enums\AnimeStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GetWeeklyScheduleAction
{
    /**
     * Get the weekly anime schedule grouped by broadcast day.
     * 
     * @return Collection<string, Collection<int, Anime>>
     */
    public function __invoke(): Collection
    {
        return Cache::remember('weekly_schedule', 3600, function () {
            return Anime::query()
                ->where('status', AnimeStatus::ONGOING)
                ->whereNotNull('broadcast_day')
                ->with([
                    'episodes' => function ($query) {
                        $query->upcoming()->orderBy('air_date')->orderBy('episode_number')->limit(1);
                    }
                ])
                ->withCount('episodes')
                ->orderBy('broadcast_time')
                ->get()
                ->groupBy(fn(Anime $anime) => $anime->broadcast_day->value);
        });
    }
}
