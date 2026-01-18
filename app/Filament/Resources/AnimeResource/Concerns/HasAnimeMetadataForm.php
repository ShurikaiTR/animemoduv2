<?php

declare(strict_types=1);

namespace App\Filament\Resources\AnimeResource\Concerns;

use App\Models\Genre;
use App\Services\TmdbService;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

trait HasAnimeMetadataForm
{
    public static function getMetadataSection(): array
    {
        return [
            Section::make('Veri Eşitleme')
                ->icon('heroicon-o-arrow-path')
                ->description('Harici kaynaklardan veri çekimi.')
                ->schema([
                    TextInput::make('tmdb_id')
                        ->label('TMDB ID')
                        ->integer()
                        ->suffixAction(
                            Action::make('fetchTmdbData')
                                ->icon('heroicon-m-arrow-down-tray')
                                ->tooltip('TMDB verilerini çek ve formu doldur')
                                ->action(function (Set $set, $state, TmdbService $tmdbService) {
                                    if (! $state) {
                                        return;
                                    }

                                    $details = $tmdbService->getDetails((int) $state);
                                    if (! $details) {
                                        return;
                                    }

                                    $set('title', $details['name'] ?? $details['title'] ?? '');
                                    $set('original_title', $details['original_name'] ?? $details['original_title'] ?? '');
                                    $set('overview', $details['overview'] ?? '');
                                    $set('poster_path', $details['poster_path'] ?? '');
                                    $set('backdrop_path', $details['backdrop_path'] ?? '');
                                    $set('vote_average', $details['vote_average'] ?? 0);
                                    $set('vote_count', $details['vote_count'] ?? 0);
                                    $set('release_date', $details['first_air_date'] ?? $details['release_date'] ?? null);
                                    $set('slug', Str::slug($details['name'] ?? $details['title'] ?? ''));

                                    if (isset($details['genres'])) {
                                        $genres = collect($details['genres'])->pluck('name')->toArray();
                                        $set('genres', $genres);
                                    }

                                    if (isset($details['images']['logos']) && count($details['images']['logos']) > 0) {
                                        $logo = $details['images']['logos'][0]['file_path'];
                                        $set('logo_path', $logo);
                                    }

                                    if (isset($details['videos']['results'])) {
                                        $trailer = collect($details['videos']['results'])
                                            ->where('type', 'Trailer')
                                            ->where('site', 'YouTube')
                                            ->first();
                                        if ($trailer) {
                                            $set('trailer_key', $trailer['key']);
                                        }
                                    }
                                })
                        ),

                    TextInput::make('anilist_id')
                        ->label('AniList ID')
                        ->integer(),

                    TextInput::make('slug')
                        ->label('URL Bağlantısı')
                        ->required()
                        ->unique(table: 'animes', column: 'slug', ignoreRecord: true),
                ]),

            Section::make('Sınıflandırma')
                ->icon('heroicon-o-tag')
                ->schema([
                    Select::make('media_type')
                        ->label('Medya')
                        ->options([
                            'tv' => 'TV Serisi',
                            'movie' => 'Film',
                        ])
                        ->required()
                        ->native(false),

                    Select::make('structure_type')
                        ->label('Bölüm Yapısı')
                        ->options([
                            'seasonal' => 'Sezonluk (S1 B1)',
                            'absolute' => 'Mutlak (Bölüm 100)',
                        ])
                        ->required()
                        ->native(false),

                    \Filament\Forms\Components\TagsInput::make('genres')
                        ->label('Türler')
                        ->suggestions(Genre::all()->pluck('name', 'name'))
                        ->placeholder('Tür ekle...'),
                ]),

            Section::make('İstatistikler')
                ->icon('heroicon-o-chart-bar')
                ->columns(2)
                ->schema([
                    TextInput::make('vote_average')
                        ->label('Puan')
                        ->numeric()
                        ->step(0.1),

                    TextInput::make('vote_count')
                        ->label('Oy')
                        ->numeric(),

                    DatePicker::make('release_date')
                        ->label('Çıkış Tarihi')
                        ->columnSpanFull(),
                ]),
        ];
    }
}
