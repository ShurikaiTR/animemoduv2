<?php

declare(strict_types=1);

namespace App\Filament\Resources\AnimeResource\Pages;

use App\Filament\Resources\AnimeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnimes extends ListRecords
{
    protected static string $resource = AnimeResource::class;

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
                        ->label('Anime/Film Ara')
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search) {
                            return collect(app(\App\Services\TmdbService::class)->search($search))
                                ->mapWithKeys(function ($item) {
                                    $title = $item['name'] ?? $item['title'];
                                    $date = $item['first_air_date'] ?? $item['release_date'] ?? 'N/A';
                                    $poster = app(\App\Services\TmdbService::class)->getImageUrl($item['poster_path'] ?? null, 'w92');

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
                        ->allowHtml()
                        ->required(),

                    \Filament\Forms\Components\Select::make('structure_type')
                        ->label('Bölüm Yapısı')
                        ->options([
                            'seasonal' => 'Sezonluk (S1 E1)',
                            'absolute' => 'Absolute (Bölüm 100)',
                        ])
                        ->default('seasonal')
                        ->required(),
                ])
                ->action(function (array $data, \App\Actions\Anime\ImportAnimeAction $importer) {
                    $anime = $importer->execute((int) $data['tmdb_id'], 'tv', $data['structure_type']);
                    \Filament\Notifications\Notification::make()
                        ->title("{$anime->title} başarıyla içe aktarıldı.")
                        ->success()
                        ->send();
                }),
        ];
    }
}
