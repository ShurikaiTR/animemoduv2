<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Concerns\HasCommentForm;
use App\Filament\Resources\CommentResource\Concerns\HasCommentTable;
use App\Filament\Resources\CommentResource\Pages;
use App\Models\Comment;
use Filament\Resources\Resource;

class CommentResource extends Resource
{
    use HasCommentForm;
    use HasCommentTable;

    protected static ?string $model = Comment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static string|\UnitEnum|null $navigationGroup = 'Topluluk';

    protected static ?string $pluralLabel = 'Yorumlar';

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
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}
