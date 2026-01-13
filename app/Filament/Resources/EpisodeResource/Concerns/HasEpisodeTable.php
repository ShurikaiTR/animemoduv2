<?php

declare(strict_types=1);

namespace App\Filament\Resources\EpisodeResource\Concerns;

use Filament\Tables;
use Filament\Tables\Table;

trait HasEpisodeTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('still_path')
                    ->label('Görsel')
                    ->getStateUsing(fn($record) => $record->still_path
                        ? "https://image.tmdb.org/t/p/w185{$record->still_path}"
                        : null)
                    ->width(80)
                    ->height(45)
                    ->defaultImageUrl(asset('img/placeholder.jpg')),
                Tables\Columns\TextColumn::make('anime.title')
                    ->label('Anime')
                    ->searchable()
                    ->limit(30)
                    ->sortable(),
                Tables\Columns\TextColumn::make('episode_info')
                    ->label('Bölüm')
                    ->getStateUsing(function ($record) {
                        $structureType = $record->anime?->structure_type ?? 'seasonal';

                        if ($structureType === 'absolute') {
                            $absNum = $record->absolute_episode_number ?? $record->episode_number;
                            return "Bölüm {$absNum}";
                        }

                        return "S{$record->season_number} B{$record->episode_number}";
                    })
                    ->badge()
                    ->color(fn($record) => ($record->anime?->structure_type ?? 'seasonal') === 'absolute' ? 'warning' : 'primary')
                    ->alignCenter()
                    ->sortable(query: function ($query, string $direction) {
                        return $query
                            ->orderBy('season_number', $direction)
                            ->orderBy('episode_number', $direction);
                    }),
                Tables\Columns\TextColumn::make('title')
                    ->label('Bölüm Adı')
                    ->searchable()
                    ->limit(35)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('air_date')
                    ->label('Yayın')
                    ->date('d.m.Y')
                    ->sortable(),
                Tables\Columns\IconColumn::make('video_url')
                    ->label('Video')
                    ->boolean()
                    ->getStateUsing(fn($record) => filled($record->video_url))
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Süre')
                    ->suffix(' dk')
                    ->placeholder('—')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('vote_average')
                    ->label('Puan')
                    ->numeric(1)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tmdb_id')
                    ->label('TMDB ID')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('absolute_episode_number')
                    ->label('Abs. No')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('anime')
                    ->relationship('anime', 'title')
                    ->searchable()
                    ->preload()
                    ->label('Anime'),
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
