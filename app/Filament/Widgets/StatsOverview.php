<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            StatsOverviewWidget\Stat::make('Toplam Anime', \App\Models\Anime::count())
                ->description('Sistemdeki tüm seriler')
                ->descriptionIcon('heroicon-m-film')
                ->color('success'),
            StatsOverviewWidget\Stat::make('Toplam Bölüm', \App\Models\Episode::count())
                ->description('Yüklenen tüm bölümler')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('primary'),
            StatsOverviewWidget\Stat::make('Yüklenecek Bölümler', \App\Models\Episode::needingVideo()->count())
                ->description('Video bekleyen yeni bölümler')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'onclick' => "window.location.href='/admin/episodes?tableFilters[needing_video][isActive]=1'",
                ]),
        ];
    }
}
