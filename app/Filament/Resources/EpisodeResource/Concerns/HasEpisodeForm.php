<?php

declare(strict_types=1);

namespace App\Filament\Resources\EpisodeResource\Concerns;

use App\Services\TmdbService;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

trait HasEpisodeForm
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Schemas\Components\Group::make([
                    Schemas\Components\Section::make('Bölüm Bilgileri')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Bölüm Başlığı')
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('overview')
                                ->label('Özet')
                                ->columnSpanFull(),
                            Schemas\Components\Grid::make(3)
                                ->schema([
                                    Forms\Components\TextInput::make('season_number')
                                        ->label('Sezon No')
                                        ->required()
                                        ->numeric(),
                                    Forms\Components\TextInput::make('episode_number')
                                        ->label('Bölüm No')
                                        ->required()
                                        ->numeric(),
                                    Forms\Components\TextInput::make('absolute_episode_number')
                                        ->label('Absolut No')
                                        ->numeric(),
                                ]),
                        ]),

                    Schemas\Components\Section::make('Medya')
                        ->schema([
                            Forms\Components\TextInput::make('video_url')
                                ->label('Video URL')
                                ->columnSpanFull(),
                            Schemas\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\TextInput::make('duration')
                                        ->label('Süre (Dk)')
                                        ->columnSpan(1)
                                        ->numeric(),
                                    Forms\Components\TextInput::make('still_path')
                                        ->label('Görsel Yolu')
                                        ->columnSpan(1)
                                        ->live(onBlur: true),
                                ]),
                            Placeholder::make('still_path_preview')
                                ->label('Görsel Önizleme')
                                ->content(function (Get $get, TmdbService $tmdbService) {
                                    $path = $get('still_path');
                                    if (! $path) {
                                        return new HtmlString('<div class="text-gray-400 text-sm italic">Görsel seçilmedi</div>');
                                    }
                                    $url = $tmdbService->getImageUrl($path);

                                    return new HtmlString("<img src='{$url}' class='w-full max-w-[300px] rounded-lg shadow-md border border-gray-200 dark:border-gray-700' />");
                                })
                                ->columnSpanFull(),
                        ]),
                ])->columnSpan(2),

                Schemas\Components\Group::make([
                    Schemas\Components\Section::make('İlişkiler')
                        ->schema([
                            Forms\Components\Select::make('anime_id')
                                ->label('Anime')
                                ->relationship('anime', 'title')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),

                    Schemas\Components\Section::make('Yayın Bilgileri')
                        ->schema([
                            Forms\Components\DatePicker::make('air_date')
                                ->label('Yayın Tarihi'),
                        ]),

                    Schemas\Components\Section::make('Metadata')
                        ->collapsible()
                        ->schema([
                            Forms\Components\TextInput::make('tmdb_id')
                                ->label('TMDB ID')
                                ->numeric(),
                            Forms\Components\TextInput::make('vote_average')
                                ->label('Puan')
                                ->numeric(),
                        ]),
                ])->columnSpan(1),
            ]);
    }
}
