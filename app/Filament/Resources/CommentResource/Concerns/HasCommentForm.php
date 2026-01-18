<?php

declare(strict_types=1);

namespace App\Filament\Resources\CommentResource\Concerns;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

trait HasCommentForm
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('İçerik ve Durum')
                    ->columns(2)
                    ->schema([
                        Textarea::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->rows(5),
                        Toggle::make('is_spoiler')
                            ->label('Spoiler mı?')
                            ->required(),
                        Toggle::make('is_pinned')
                            ->label('Sabitlenmiş mi?')
                            ->required(),
                    ]),

                Section::make('İlişkiler')
                    ->columns(3)
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),
                        Select::make('anime_id')
                            ->relationship('anime', 'title')
                            ->searchable()
                            ->required(),
                        Select::make('episode_id')
                            ->relationship('episode', 'title')
                            ->searchable()
                            ->placeholder('Bölüm seçilmedi'),
                        Select::make('parent_id')
                            ->relationship('parent', 'id')
                            ->searchable()
                            ->getOptionLabelFromRecordUsing(fn ($record) => Str::limit($record->content, 50))
                            ->placeholder('Üst yorum yok')
                            ->columnSpanFull(),
                    ]),

                Section::make('İstatistikler')
                    ->columns(2)
                    ->schema([
                        TextInput::make('like_count')
                            ->label('Beğeni Sayısı')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('dislike_count')
                            ->label('Beğenmeme Sayısı')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }
}
