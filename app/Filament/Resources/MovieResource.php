<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\MovieResource\Pages;
use App\Models\Anime;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MovieResource extends Resource
{
    protected static ?string $model = Anime::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-film';

    protected static string|\UnitEnum|null $navigationGroup = 'İçerik Yönetimi';

    protected static ?string $pluralLabel = 'Filmler';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('media_type', 'movie');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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
            'edit' => Pages\EditMovie::route('/{record}/edit'),
        ];
    }
}
