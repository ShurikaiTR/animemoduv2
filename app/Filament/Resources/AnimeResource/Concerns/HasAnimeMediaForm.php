<?php

declare(strict_types=1);

namespace App\Filament\Resources\AnimeResource\Concerns;

use App\Services\TmdbService;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\HtmlString;

trait HasAnimeMediaForm
{
    public static function getMediaSection(): Section
    {
        return Section::make('Medya & Görseller')
            ->icon('heroicon-o-photo')
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('trailer_key')
                            ->label('YouTube Fragman ID')
                            ->placeholder('Örn: mBaY3-0m8-g')
                            ->prefixIcon('heroicon-m-play-circle')
                            ->columnSpanFull(),

                        Grid::make(1)
                            ->schema([
                                TextInput::make('poster_path')
                                    ->label('Poster Yolu')
                                    ->live()
                                    ->suffixAction(
                                        Action::make('open_poster')
                                            ->icon('heroicon-m-arrow-top-right-on-square')
                                            ->url(fn ($state, TmdbService $s) => $state ? $s->getImageUrl($state) : null, true)
                                            ->visible(fn ($state) => filled($state))
                                    ),

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

                        Grid::make(1)
                            ->schema([
                                TextInput::make('backdrop_path')
                                    ->label('Backdrop Yolu')
                                    ->live()
                                    ->suffixAction(
                                        Action::make('open_backdrop')
                                            ->icon('heroicon-m-arrow-top-right-on-square')
                                            ->url(fn ($state, TmdbService $s) => $state ? $s->getImageUrl($state, 'w780') : null, true)
                                            ->visible(fn ($state) => filled($state))
                                    ),

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

                        Grid::make(1)
                            ->schema([
                                TextInput::make('logo_path')
                                    ->label('Logo Yolu')
                                    ->live()
                                    ->suffixAction(
                                        Action::make('open_logo')
                                            ->icon('heroicon-m-arrow-top-right-on-square')
                                            ->url(fn ($state, TmdbService $s) => $state ? $s->getImageUrl($state, 'original') : null, true)
                                            ->visible(fn ($state) => filled($state))
                                    ),

                                Placeholder::make('logo_preview')
                                    ->label('Logo Önizleme')
                                    ->content(function (Get $get, TmdbService $tmdbService) {
                                        $path = $get('logo_path');
                                        if (! $path) {
                                            return new HtmlString('<div class="text-gray-400 text-sm italic">Görsel seçilmedi</div>');
                                        }
                                        $url = $tmdbService->getImageUrl($path, 'original');

                                        return new HtmlString("<img src='{$url}' class='w-full max-w-[200px] bg-slate-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700' />");
                                    }),
                            ]),
                    ]),
            ]);
    }
}
