<?php

declare(strict_types=1);

namespace App\Filament\Resources\MovieResource\Concerns;

use App\Services\TmdbService;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\HtmlString;

trait HasMovieMediaForm
{
    public static function getMediaSection(): Section
    {
        return Section::make('Medya & Görseller')
            ->icon('heroicon-o-photo')
            ->schema([
                TextInput::make('video_url')
                    ->label('Film Video URL')
                    ->placeholder('Örn: https://example.com/video.mp4')
                    ->columnSpanFull(),

                TextInput::make('trailer_key')
                    ->label('YouTube Fragman ID')
                    ->placeholder('Örn: mBaY3-0m8-g')
                    ->prefixIcon('heroicon-m-play-circle')
                    ->columnSpanFull(),

                Grid::make(2)
                    ->schema([
                        Group::make([
                            TextInput::make('poster_path')
                                ->label('Poster Yolu')
                                ->live(),
                            Placeholder::make('poster_preview')
                                ->label('Poster Önizleme')
                                ->content(function (Get $get, TmdbService $tmdbService) {
                                    $path = $get('poster_path');
                                    if (! $path) {
                                        return new HtmlString('<div class="text-gray-400 text-sm italic">Görsel seçilmedi</div>');
                                    }
                                    $url = $tmdbService->getImageUrl($path);

                                    return new HtmlString("<img src='{$url}' class='w-full max-w-[200px] rounded-lg shadow-md border border-gray-200 dark:border-gray-700' />");
                                }),
                        ]),
                        Group::make([
                            TextInput::make('backdrop_path')
                                ->label('Backdrop Yolu')
                                ->live(),
                            Placeholder::make('backdrop_preview')
                                ->label('Backdrop Önizleme')
                                ->content(function (Get $get, TmdbService $tmdbService) {
                                    $path = $get('backdrop_path');
                                    if (! $path) {
                                        return new HtmlString('<div class="text-gray-400 text-sm italic">Görsel seçilmedi</div>');
                                    }
                                    $url = $tmdbService->getImageUrl($path, 'w780');

                                    return new HtmlString("<img src='{$url}' class='w-full rounded-lg shadow-md border border-gray-200 dark:border-gray-700' />");
                                }),
                        ]),
                    ]),
            ]);
    }
}
