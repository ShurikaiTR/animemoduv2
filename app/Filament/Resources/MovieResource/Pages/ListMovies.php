<?php

declare(strict_types=1);

namespace App\Filament\Resources\MovieResource\Pages;

use App\Filament\Resources\MovieResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMovies extends ListRecords
{
    protected static string $resource = MovieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('importFromTmdb')
                ->label("TMDB'den İçe Aktar")
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->form([
                    \Filament\Forms\Components\Select::make('tmdb_id')
                        ->label('Film Ara')
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search) {
                            return collect(app(\App\Services\TmdbService::class)->search($search))
                                ->where('media_type', 'movie')
                                ->mapWithKeys(function ($item) {
                                    $title = $item['title'];
                                    $date = $item['release_date'] ?? 'N/A';
                                    $poster = app(\App\Services\TmdbService::class)->getImageUrl($item['poster_path'], 'w92');

                                    return [
                                        $item['id'] => "
                                        <div class='flex items-center gap-3 py-1'>
                                            <img src='{$poster}' class='w-10 h-14 rounded shadow-sm object-cover bg-gray-800'>
                                            <div class='flex flex-col'>
                                                <span class='font-bold text-gray-950 dark:text-white'>{$title}</span>
                                                <span class='text-xs text-gray-500'>{$date}</span>
                                            </div>
                                        </div>
                                    ",
                                    ];
                                })
                                ->toArray();
                        })
                        ->getOptionLabelUsing(function ($value): ?string {
                            if (!$value) {
                                return null;
                            }

                            $movie = app(\App\Services\TmdbService::class)->getDetails((int) $value, 'movie');

                            return $movie['title'] ?? null;
                        })
                        ->allowHtml()
                        ->required(),
                ])
                ->action(function (array $data, \App\Actions\Anime\ImportAnimeAction $importer) {
                    $anime = $importer->execute((int) $data['tmdb_id'], 'movie', 'seasonal');
                    \Filament\Notifications\Notification::make()
                        ->title("{$anime->title} başarıyla içe aktarıldı.")
                        ->success()
                        ->send();
                }),
        ];
    }
}
