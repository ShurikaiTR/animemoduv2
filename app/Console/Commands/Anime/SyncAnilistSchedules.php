<?php

namespace App\Console\Commands\Anime;

use App\Enums\AnimeStatus;
use App\Models\Anime;
use App\Services\AnilistService;
use Illuminate\Console\Command;

class SyncAnilistSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anime:sync-anilist-schedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync broadcast day and time from AniList for ongoing animes';

    /**
     * Execute the console command.
     */
    public function handle(AnilistService $anilist): int
    {
        $this->info('AniList takvim senkronizasyonu başlatılıyor...');

        $animes = Anime::query()
            ->where('status', AnimeStatus::ONGOING)
            ->whereNotNull('anilist_id')
            ->get();

        if ($animes->isEmpty()) {
            $this->warn('AniList ID\'si olan "Devam Eden" anime bulunamadı.');

            return self::SUCCESS;
        }

        foreach ($animes as $anime) {
            $this->comment("Senkronize ediliyor: {$anime->title}");

            $schedule = $anilist->getMediaSchedule((int) $anime->anilist_id);

            if (!$schedule) {
                $this->warn('  - AniList verisi çekilemedi.');

                continue;
            }

            $airingAt = null;
            if (isset($schedule['nextAiringEpisode'])) {
                $airingAt = $schedule['nextAiringEpisode']['airingAt'];
            } elseif (isset($schedule['airingSchedule']['nodes'][0])) {
                $airingAt = $schedule['airingSchedule']['nodes'][0]['airingAt'];
            }

            if ($airingAt) {
                $date = (new \DateTime)->setTimestamp($airingAt)->setTimezone(new \DateTimeZone('Europe/Istanbul'));

                $anime->update([
                    'broadcast_day' => \App\Enums\DayOfWeek::fromDate($date),
                    'broadcast_time' => $date->format('H:i'),
                ]);

                $this->info("  - Başarılı: {$anime->broadcast_day->getLabel()} {$anime->broadcast_time}");
            } else {
                $this->warn('  - Yayın bilgisi bulunamadı.');
            }
        }

        $this->info('Senkronizasyon tamamlandı.');

        return self::SUCCESS;
    }
}
