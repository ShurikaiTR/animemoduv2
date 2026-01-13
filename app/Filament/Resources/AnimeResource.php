<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\AnimeResource\Concerns\HasAnimeForm;
use App\Filament\Resources\AnimeResource\Concerns\HasAnimeTable;
use App\Filament\Resources\AnimeResource\Pages;
use App\Models\Anime;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

class AnimeResource extends Resource
{
    use HasAnimeForm;
    use HasAnimeTable;

    protected static ?string $model = Anime::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tv';

    protected static string|\UnitEnum|null $navigationGroup = 'İçerik Yönetimi';

    protected static ?string $pluralLabel = 'Animeler';

    protected static ?string $navigationLabel = 'Anime Listesi';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('media_type', 'tv');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnimes::route('/'),
            'create' => Pages\CreateAnime::route('/create'),
            'edit' => Pages\EditAnime::route('/{record}/edit'),
        ];
    }
}
