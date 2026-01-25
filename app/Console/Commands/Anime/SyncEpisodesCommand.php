<?php

declare(strict_types=1);

namespace App\Console\Commands\Anime;

use App\Enums\AnimeStatus;
use App\Models\Anime;
use App\Models\Episode;
use App\Services\TmdbService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncEpisodesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anime:sync-episodes {--anime_id= : Sync only a specific anime}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Sync episode metadata (titles, images, air dates) from TMDB for ongoing anime';

    /**
     * Execute the console command.
     */
    public function handle(TmdbService $tmdb): int
    {
        $this->info('Episode senkronizasyonu başlatılıyor...');

        $query = Anime::query()->where('status', AnimeStatus::ONGOING);

        if ($animeId = $this->option('anime_id')) {
            $query->where('id', $animeId);
        }

        $animes = $query->get();

        if ($animes->isEmpty()) {
            $this->warn('Senkronize edilecek "Devam Eden" anime bulunamadı.');
            return self::SUCCESS;
        }

        foreach ($animes as $anime) {
            $this->comment("Senkronize ediliyor: {$anime->title}");

            // Mevcut sezonları bul
            $seasons = $anime->episodes()
                ->where('season_number', '>', 0)
                ->select('season_number')
                ->distinct()
                ->pluck('season_number');

            foreach ($seasons as $seasonNumber) {
                $seasonDetails = $tmdb->getSeasonDetails((int) $anime->tmdb_id, (int) $seasonNumber);

                if (!$seasonDetails || !isset($seasonDetails['episodes'])) {
                    continue;
                }

                foreach ($seasonDetails['episodes'] as $epData) {
                    $episode = Episode::where('anime_id', $anime->id)
                        ->where('season_number', $epData['season_number'])
                        ->where('episode_number', $epData['episode_number'])
                        ->first();

                    if ($episode) {
                        $updates = [];

                        // 1. Resim güncellemesi (Still Path)
                        if (empty($episode->still_path) && !empty($epData['still_path'])) {
                            $updates['still_path'] = $epData['still_path'];
                            $this->info("  - {$episode->episode_number}. Bölüm resmi güncellendi.");
                        }

                        // 2. İsim güncellemesi (Title)
                        $tmdbTitle = $epData['name'] ?? null;
                        if ($tmdbTitle && $episode->title !== $tmdbTitle && !str_contains($tmdbTitle, 'Episode')) {
                            $updates['title'] = $tmdbTitle;
                            $this->info("  - {$episode->episode_number}. Bölüm ismi güncellendi: {$tmdbTitle}");
                        }

                        $currentAirDate = $episode->air_date instanceof \Illuminate\Support\Carbon ? $episode->air_date->format('Y-m-d') : ($episode->air_date ? (string) $episode->air_date : null);
                        if (isset($epData['air_date']) && $currentAirDate !== $epData['air_date']) {
                            $updates['air_date'] = $epData['air_date'];
                            $this->info("  - {$episode->episode_number}. Bölüm tarihi güncellendi: {$epData['air_date']}");
                        }

                        if (!empty($updates)) {
                            $episode->update($updates);
                        }
                    } else {
                        // Eğer veritabanımızda olmayan yeni bir bölüm gelmişse (TMDB sezonu genişletmişse)
                        // Bu kısım ImportAnimeAction içindeki mantıkla aynı şekilde yeni bölüm ekleyebilir.
                        Episode::create([
                            'anime_id' => $anime->id,
                            'tmdb_id' => $epData['id'],
                            'title' => $epData['name'],
                            'overview' => $epData['overview'],
                            'still_path' => $epData['still_path'],
                            'vote_average' => $epData['vote_average'],
                            'air_date' => $epData['air_date'],
                            'season_number' => $epData['season_number'],
                            'episode_number' => $epData['episode_number'],
                            'absolute_episode_number' => $anime->episodes()->max('absolute_episode_number') + 1,
                            'duration' => $epData['runtime'] ?? null,
                        ]);
                        $this->info("  + Yeni bölüm eklendi: {$epData['episode_number']}. Bölüm");
                    }
                }
            }
        }

        $this->info('Senkronizasyon tamamlandı.');
        return self::SUCCESS;
    }
}
