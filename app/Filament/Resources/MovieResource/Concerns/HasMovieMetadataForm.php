<?php

declare(strict_types=1);

namespace App\Filament\Resources\MovieResource\Concerns;

use App\Models\Genre;
use App\Services\TmdbService;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

trait HasMovieMetadataForm
{
    public static function getMetadataSections(): array
    {
        return [
            Section::make('Veri Eşitleme')
                ->icon('heroicon-o-arrow-path')
                ->schema([
                    TextInput::make('tmdb_id')
                        ->label('TMDB ID')
                        ->integer()
                        ->suffixAction(
                            Action::make('fetchTmdbData')
                                ->icon('heroicon-m-arrow-down-tray')
                                ->action(function (Set $set, $state, TmdbService $tmdbService) {
                                    if (! $state) {
                                        return;
                                    }
                                    $details = $tmdbService->getDetails((int) $state, 'movie');
                                    if (! $details) {
                                        return;
                                    }

                                    $set('title', $details['title'] ?? '');
                                    $set('original_title', $details['original_title'] ?? '');
                                    $set('overview', $details['overview'] ?? '');
                                    $set('poster_path', $details['poster_path'] ?? '');
                                    $set('backdrop_path', $details['backdrop_path'] ?? '');
                                    $set('vote_average', $details['vote_average'] ?? 0);
                                    $set('vote_count', $details['vote_count'] ?? 0);
                                    $set('release_date', $details['release_date'] ?? null);
                                    $set('slug', Str::slug($details['title'] ?? ''));

                                    if (isset($details['genres'])) {
                                        $genres = collect($details['genres'])->pluck('name')->toArray();
                                        $set('genres', $genres);
                                    }
                                })
                        ),
                    TextInput::make('slug')
                        ->label('URL Bağlantısı')
                        ->required()
                        ->unique(table: 'animes', column: 'slug', ignoreRecord: true),
                ]),

            Section::make('Sınıflandırma')
                ->icon('heroicon-o-tag')
                ->schema([
                    Select::make('genres')
                        ->label('Türler')
                        ->multiple()
                        ->searchable()
                        ->options(Genre::all()->pluck('name', 'name'))
                        ->preload(),

                    TextInput::make('duration')
                        ->label('Süre (Dakika)')
                        ->numeric(),
                ]),

            Section::make('İstatistikler')
                ->icon('heroicon-o-chart-bar')
                ->schema([
                    TextInput::make('vote_average')
                        ->label('Puan')
                        ->numeric()
                        ->step(0.1),
                    DatePicker::make('release_date')
                        ->label('Çıkış Tarihi'),
                ]),
        ];
    }
}
