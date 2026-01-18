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
                                    $date = \Illuminate\Support\Carbon::parse($item['first_air_date'] ?? $item['release_date'] ?? now())->format('Y');
                                    $poster = app(\App\Services\TmdbService::class)->getImageUrl($item['poster_path'] ?? null, 'w92');
                                    $rating = number_format($item['vote_average'] ?? 0, 1);
                                    $type = ($item['media_type'] ?? 'tv') === 'movie' ? 'Film' : 'Dizi';
                                    $typeColor = ($item['media_type'] ?? 'tv') === 'movie' ? 'bg-blue-500/10 text-blue-500' : 'bg-purple-500/10 text-purple-500';

                                    return [
                                        $item['id'] => "
                                        <div class='flex items-start gap-3 py-2'>
                                            <div class='relative shrink-0'>
                                                <img src='{$poster}' class='w-12 h-16 rounded-md shadow-sm object-cover bg-gray-800 ring-1 ring-white/10'>
                                                <div class='absolute -top-1 -right-1 bg-gray-900 text-white text-[10px] font-bold px-1.5 py-0.5 rounded shadow-sm border border-white/10'>
                                                    {$rating}
                                                </div>
                                            </div>
                                            <div class='flex flex-col gap-0.5 pt-0.5'>
                                                <span class='font-medium text-sm text-gray-950 dark:text-gray-100 line-clamp-1'>{$title}</span>
                                                <div class='flex items-center gap-2 text-xs'>
                                                    <span class='text-gray-500'>{$date}</span>
                                                    <span class='px-1.5 py-0.5 rounded text-[10px] font-bold uppercase {$typeColor}'>{$type}</span>
                                                </div>
                                                <div class='text-xs text-gray-400 line-clamp-1 mt-0.5'>
                                                    ".($item['overview'] ?? '').'
                                                </div>
                                            </div>
                                        </div>
                                    ',
                                    ];
                                })
                                ->toArray();
                        })
                        ->getOptionLabelUsing(fn ($value): ?string => (string) $value)
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
