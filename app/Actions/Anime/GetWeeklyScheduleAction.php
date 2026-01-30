<?php

declare(strict_types=1);

namespace App\Actions\Anime;

use App\Enums\AnimeStatus;
use App\Models\Anime;
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
                ->with([
                    'episodes' => function ($query) {
                        $query->upcoming()->orderBy('air_date')->orderBy('episode_number')->limit(1);
                    },
                ])
                ->withCount('episodes')
                ->orderBy('broadcast_time')
                ->get()
                ->groupBy(function (Anime $anime) {
                    // 1. Varsa manuel girilen günü kullan
                    if ($anime->broadcast_day) {
                        return $anime->broadcast_day->value;
                    }

                    // 2. Yoksa en yakın gelecek bölümün tarihinden günü çek
                    $upcomingEpisode = $anime->episodes->first();
                    if ($upcomingEpisode && $upcomingEpisode->air_date) {
                        return \App\Enums\DayOfWeek::fromDate($upcomingEpisode->air_date)->value;
                    }

                    // 3. Hiçbir veri yoksa takvime dahil etme (null dönerek groupBy dışında bırakıyoruz)
                    return null;
                })
                ->filter(fn ($item, $key) => ! empty($key));
        });
    }
}
