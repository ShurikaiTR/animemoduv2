<?php

declare(strict_types=1);

namespace App\Actions\Anime;

use App\Enums\AnimeStatus;
use App\Models\Anime;
use App\Services\AnilistService;
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
        return Cache::remember('weekly_schedule', 1800, function () {
            $anilist = app(AnilistService::class);
            $ongoingAnimes = Anime::query()
                ->where('status', AnimeStatus::ONGOING)
                ->with([
                    'episodes' => function ($query) {
                        $query->upcoming()->orderBy('air_date')->orderBy('episode_number')->limit(1);
                    },
                ])
                ->withCount('episodes')
                ->get();

            return $ongoingAnimes->map(function (Anime $anime) use ($anilist) {
                // 1. AniList'ten güncel yayın bilgisini çekmeyi dene
                if ($anime->anilist_id) {
                    $anilistData = $anilist->getMediaSchedule((int) $anime->anilist_id);
                    if ($anilistData && isset($anilistData['nextAiringEpisode'])) {
                        $airingAt = $anilistData['nextAiringEpisode']['airingAt'];
                        $date = (new \DateTime)->setTimestamp($airingAt)->setTimezone(new \DateTimeZone('Europe/Istanbul'));

                        $anime->broadcast_day = \App\Enums\DayOfWeek::fromDate($date);
                        $anime->broadcast_time = $date->format('H:i');
                    } elseif ($anilistData && isset($anilistData['airingSchedule']['nodes'][0])) {
                        // Eğer gelecek bölüm yoksa ama geçmiş bölüm bilgisi varsa oradan günü tahmin et
                        $airingAt = $anilistData['airingSchedule']['nodes'][0]['airingAt'];
                        $date = (new \DateTime)->setTimestamp($airingAt)->setTimezone(new \DateTimeZone('Europe/Istanbul'));

                        $anime->broadcast_day = $anime->broadcast_day ?? \App\Enums\DayOfWeek::fromDate($date);
                        $anime->broadcast_time = $anime->broadcast_time ?? $date->format('H:i');
                    }
                }

                // 2. Eğer AniList verisi yoksa fallback: Manuel gün veya en yakın bölüm tarihi
                if (!$anime->broadcast_day) {
                    $upcomingEpisode = $anime->episodes->first();
                    if ($upcomingEpisode && $upcomingEpisode->air_date) {
                        $anime->broadcast_day = \App\Enums\DayOfWeek::fromDate($upcomingEpisode->air_date);
                    }
                }

                return $anime;
            })
                ->filter(fn($anime) => $anime->broadcast_day !== null)
                ->sortBy('broadcast_time')
                ->groupBy(fn($anime) => $anime->broadcast_day->value);
        });
    }
}
