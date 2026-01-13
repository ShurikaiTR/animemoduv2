<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\EpisodeResource\Concerns\HasEpisodeForm;
use App\Filament\Resources\EpisodeResource\Concerns\HasEpisodeTable;
use App\Filament\Resources\EpisodeResource\Pages;
use App\Models\Episode;
use Filament\Resources\Resource;

class EpisodeResource extends Resource
{
    use HasEpisodeForm;
    use HasEpisodeTable;

    protected static ?string $model = Episode::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'İçerik Yönetimi';

    protected static ?string $pluralLabel = 'Bölümler';

    protected static ?int $navigationSort = 2;

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEpisodes::route('/'),
            'create' => Pages\CreateEpisode::route('/create'),
            'edit' => Pages\EditEpisode::route('/{record}/edit'),
        ];
    }
}
