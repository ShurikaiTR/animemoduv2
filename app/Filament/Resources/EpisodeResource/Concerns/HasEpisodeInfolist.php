<?php

declare(strict_types=1);

namespace App\Filament\Resources\EpisodeResource\Concerns;

use App\Services\TmdbService;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

trait HasEpisodeInfolist
{
    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make([
                    Section::make('Bölüm Bilgileri')
                        ->schema([
                            TextEntry::make('title')
                                ->label('Bölüm Başlığı')
                                ->weight('bold')
                                ->size('lg')
                                ->columnSpanFull(),
                            TextEntry::make('overview')
                                ->label('Özet')
                                ->placeholder('Özet bulunmuyor.')
                                ->columnSpanFull(),
                            Grid::make(3)
                                ->schema([
                                    TextEntry::make('season_number')
                                        ->label('Sezon No')
                                        ->badge(),
                                    TextEntry::make('episode_number')
                                        ->label('Bölüm No')
                                        ->badge(),
                                    TextEntry::make('absolute_episode_number')
                                        ->label('Absolut No')
                                        ->badge()
                                        ->color('warning'),
                                ]),
                        ]),

                    Section::make('Medya')
                        ->schema([
                            TextEntry::make('video_url')
                                ->label('Video URL')
                                ->url(fn ($record) => $record->video_url, true)
                                ->color('primary')
                                ->placeholder('Video yok')
                                ->columnSpanFull(),
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('duration')
                                        ->label('Süre')
                                        ->suffix(' dakika'),
                                    TextEntry::make('vote_average')
                                        ->label('Puan')
                                        ->numeric(1)
                                        ->color('success')
                                        ->icon('heroicon-m-star'),
                                ]),
                            ImageEntry::make('still_path')
                                ->label('Bölüm Görseli')
                                ->getStateUsing(fn ($record, TmdbService $tmdbService) => $record->still_path ? $tmdbService->getImageUrl($record->still_path) : null)
                                ->columnSpanFull()
                                ->extraImgAttributes([
                                    'class' => 'rounded-xl shadow-lg border border-gray-200 dark:border-gray-700',
                                ]),
                        ]),
                ])->columnSpan(2),

                Group::make([
                    Section::make('İlişkiler')
                        ->schema([
                            TextEntry::make('anime.title')
                                ->label('Anime')
                                ->color('primary')
                                ->weight('bold'),
                        ]),

                    Section::make('Yayın Bilgileri')
                        ->schema([
                            TextEntry::make('air_date')
                                ->label('Yayın Tarihi')
                                ->date('d F Y'),
                        ]),

                    Section::make('Metadata')
                        ->collapsible()
                        ->schema([
                            TextEntry::make('tmdb_id')
                                ->label('TMDB ID')
                                ->copyable(),
                            TextEntry::make('created_at')
                                ->label('Oluşturulma')
                                ->dateTime('d.m.Y H:i'),
                        ]),
                ])->columnSpan(1),
            ]);
    }
}
