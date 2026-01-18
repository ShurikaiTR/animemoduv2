<?php

declare(strict_types=1);

namespace App\Filament\Resources\MovieResource\Concerns;

use App\Services\TmdbService;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

trait HasMovieInfolist
{
    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make([
                    Section::make('Film Bilgileri')
                        ->icon('heroicon-o-film')
                        ->schema([
                            TextEntry::make('title')
                                ->label('Film Adı')
                                ->weight('bold')
                                ->size('lg'),
                            TextEntry::make('original_title')
                                ->label('Orijinal Başlık')
                                ->color('gray'),
                            TextEntry::make('overview')
                                ->label('Özet')
                                ->markdown()
                                ->placeholder('Özet bulunmuyor.')
                                ->columnSpanFull(),
                        ]),

                    Section::make('Medya & Görseller')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            TextEntry::make('video_url')
                                ->label('Video URL')
                                ->url(fn ($record) => $record->video_url, true)
                                ->color('primary')
                                ->icon('heroicon-m-play-circle')
                                ->placeholder('Video yok'),

                            Grid::make(2)
                                ->schema([
                                    ImageEntry::make('poster_path')
                                        ->label('Poster')
                                        ->getStateUsing(fn ($record, TmdbService $tmdbService) => $record->poster_path ? $tmdbService->getImageUrl($record->poster_path) : null)
                                        ->extraImgAttributes([
                                            'class' => 'rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 w-full max-w-[200px]',
                                        ]),
                                    ImageEntry::make('backdrop_path')
                                        ->label('Backdrop')
                                        ->getStateUsing(fn ($record, TmdbService $tmdbService) => $record->backdrop_path ? $tmdbService->getImageUrl($record->backdrop_path, 'w780') : null)
                                        ->extraImgAttributes([
                                            'class' => 'rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 w-full',
                                        ]),
                                ]),
                            TextEntry::make('trailer_key')
                                ->label('Fragman')
                                ->formatStateUsing(fn ($state) => $state ? "https://youtube.com/watch?v={$state}" : null)
                                ->url(fn ($state) => $state ? "https://youtube.com/watch?v={$state}" : null, true)
                                ->icon('heroicon-m-play-circle')
                                ->color('primary')
                                ->placeholder('Fragman yok'),
                        ]),
                ])->columnSpan(2),

                Group::make([
                    Section::make('Durum')
                        ->icon('heroicon-o-globe-alt')
                        ->schema([
                            TextEntry::make('status')
                                ->label('Yayın Durumu')
                                ->badge(),
                            TextEntry::make('hero_order')
                                ->label('Vitrin Sırası')
                                ->formatStateUsing(fn ($state) => $state > 0 ? '#'.$state : 'Yok')
                                ->badge()
                                ->color(fn ($state) => $state > 0 ? 'warning' : 'gray'),
                        ]),

                    Section::make('Sınıflandırma')
                        ->icon('heroicon-o-tag')
                        ->schema([
                            TextEntry::make('genres')
                                ->label('Türler')
                                ->badge()
                                ->separator(','),
                            TextEntry::make('duration')
                                ->label('Süre')
                                ->suffix(' dakika'),
                        ]),

                    Section::make('İstatistikler')
                        ->icon('heroicon-o-chart-bar')
                        ->schema([
                            TextEntry::make('vote_average')
                                ->label('Puan')
                                ->icon('heroicon-m-star')
                                ->color('warning')
                                ->numeric(1),
                            TextEntry::make('release_date')
                                ->label('Çıkış Tarihi')
                                ->date('d F Y'),
                        ]),

                    Section::make('Metadata')
                        ->collapsible()
                        ->schema([
                            TextEntry::make('tmdb_id')
                                ->label('TMDB ID')
                                ->copyable(),
                            TextEntry::make('slug')
                                ->label('Slug')
                                ->icon('heroicon-m-link')
                                ->copyable(),
                        ]),
                ])->columnSpan(1),
            ]);
    }
}
