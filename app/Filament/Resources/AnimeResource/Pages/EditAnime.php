<?php

declare(strict_types=1);

namespace App\Filament\Resources\AnimeResource\Pages;

use App\Actions\Anime\SplitEpisodesAction;
use App\Filament\Resources\AnimeResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAnime extends EditRecord
{
    protected static string $resource = AnimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('splitSeasons')
                ->label('Sezonlara Böl')
                ->icon('heroicon-o-scissors')
                ->color('warning')
                ->visible(fn() => $this->record->anilist_id !== null)
                ->requiresConfirmation()
                ->modalHeading('AniList\'ten Sezonlara Böl')
                ->modalDescription('Bölümler AniList sezon bilgilerine göre otomatik bölünecek. Movie ve manga hariç tutulur.')
                ->action(function (): void {
                    try {
                        $result = app(SplitEpisodesAction::class)->execute($this->record);

                        $seasonList = collect($result['seasons'])
                            ->map(fn($s, $i) => 'S' . ($i + 1) . ': ' . $s['episodes'] . ' bölüm')
                            ->join(', ');

                        Notification::make()
                            ->title("{$result['updated']} bölüm güncellendi")
                            ->body($seasonList)
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Hata')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Actions\DeleteAction::make(),
        ];
    }
}

