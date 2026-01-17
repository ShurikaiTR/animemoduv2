<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\MovieResource\Concerns\HasMovieForm;
use App\Filament\Resources\MovieResource\Concerns\HasMovieInfolist;
use App\Filament\Resources\MovieResource\Concerns\HasMovieTable;
use App\Filament\Resources\MovieResource\Pages;
use App\Models\Anime;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

class MovieResource extends Resource
{
    use HasMovieForm;
    use HasMovieInfolist;
    use HasMovieTable;

    protected static ?string $model = Anime::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-film';

    protected static ?string $pluralLabel = 'Filmler';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('media_type', 'movie');
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
            'index' => Pages\ListMovies::route('/'),
            'create' => Pages\CreateMovie::route('/create'),
            'view' => Pages\ViewMovie::route('/{record}'),
            'edit' => Pages\EditMovie::route('/{record}/edit'),
        ];
    }
}
