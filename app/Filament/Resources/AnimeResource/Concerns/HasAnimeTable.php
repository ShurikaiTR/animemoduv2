<?php

declare(strict_types=1);

namespace App\Filament\Resources\AnimeResource\Concerns;

use App\Enums\AnimeStatus;
use Filament\Tables;
use Filament\Tables\Table;

trait HasAnimeTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('poster_path')
                    ->label('Poster')
                    ->getStateUsing(fn($record) => $record->poster_path
                        ? "https://image.tmdb.org/t/p/w92{$record->poster_path}"
                        : null)
                    ->width(50)
                    ->height(75)
                    ->defaultImageUrl(asset('img/placeholder.jpg')),
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->limit(40)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vote_average')
                    ->label('Puan')
                    ->numeric(1)
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('release_date')
                    ->label('Yayın')
                    ->date('Y')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Öne Çıkan')
                    ->boolean()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('episodes_count')
                    ->label('Bölüm')
                    ->counts('episodes')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('tmdb_id')
                    ->label('TMDB ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('anilist_id')
                    ->label('AniList ID')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(AnimeStatus::class)
                    ->label('Durum'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Öne Çıkan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
