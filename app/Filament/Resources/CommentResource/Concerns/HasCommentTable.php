<?php

declare(strict_types=1);

namespace App\Filament\Resources\CommentResource\Concerns;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;

trait HasCommentTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.profile.username')
                    ->label('Kullanıcı')
                    ->html()
                    ->formatStateUsing(function ($record) {
                        $avatarUrl = $record->user?->profile?->avatar_url
                            ? asset($record->user->profile->avatar_url)
                            : asset('default-avatar.webp');
                        $username = $record->user?->profile?->username ?? 'Misafir';

                        return "
                            <div style='display: inline-flex !important; flex-direction: row !important; align-items: center !important; gap: 0.5rem !important;'>
                                <img src='{$avatarUrl}' class='size-7 rounded-full object-cover shrink-0' style='width: 1.75rem !important; height: 1.75rem !important; display: block !important;'>
                                <span class='text-sm font-medium'>{$username}</span>
                            </div>
                        ";
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->label('Yorum')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('anime.title')
                    ->label('Anime')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_spoiler')
                    ->label('Spoiler')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_pinned')
                    ->label('Sabit')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('like_count')
                    ->label('L/D')
                    ->formatStateUsing(fn ($record) => "{$record->like_count} / {$record->dislike_count}")
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_spoiler')
                    ->label('Spoiler mı?'),
                Tables\Filters\TernaryFilter::make('is_pinned')
                    ->label('Sabitlenmiş mi?'),
                Tables\Filters\SelectFilter::make('anime')
                    ->relationship('anime', 'title')
                    ->searchable(),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
