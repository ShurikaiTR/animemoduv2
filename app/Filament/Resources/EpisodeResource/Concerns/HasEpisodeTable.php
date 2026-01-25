<?php

declare(strict_types=1);

namespace App\Filament\Resources\EpisodeResource\Concerns;

use App\Filament\Resources\EpisodeResource\Pages;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
                    ->width(100)
                    ->height(56)
                    ->extraImgAttributes(['class' => 'rounded-md shadow-sm border border-gray-100 dark:border-gray-800'])
                    ->defaultImageUrl(asset('img/placeholder.jpg')),
                Tables\Columns\TextColumn::make('anime.title')
                    ->label('Anime')
                    ->searchable()
                    ->limit(30)
                    ->weight('medium')
                    ->color('primary')
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
                    ->placeholder('Başlık belirtilmemiş'),
                Tables\Columns\TextColumn::make('air_date')
                    ->label('Yayın')
                    ->date('d.m.Y')
                    ->color('gray')
                    ->sortable(),
                Tables\Columns\IconColumn::make('video_url')
                    ->label('Durum')
                    ->tooltip('Video Durumu')
                    ->boolean()
                    ->getStateUsing(fn($record) => filled($record->video_url))
                    ->trueIcon('heroicon-o-play-circle')
                    ->falseIcon('heroicon-o-no-symbol')
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
                    ->icon('heroicon-m-star')
                    ->color('warning')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->fontFamily('mono')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tmdb_id')
                    ->label('TMDB ID')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('absolute_episode_number')
                    ->label('Abs. No')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Eklenme')
                    ->dateTime('d.m.Y H:i')
                    ->color('gray')
                    ->size('xs')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('anime')
                    ->relationship('anime', 'title')
                    ->searchable()
                    ->preload()
                    ->label('Animeye Göre Filtrele'),
                Tables\Filters\TernaryFilter::make('video_status')
                    ->label('Video Durumu')
                    ->placeholder('Hepsi')
                    ->trueLabel('Videosu Var')
                    ->falseLabel('Videosu Yok')
                    ->queries(
                        true: fn($query) => $query->whereNotNull('video_url'),
                        false: fn($query) => $query->whereNull('video_url'),
                    ),
                Tables\Filters\Filter::make('needing_video')
                    ->label('Yüklenecekler Listesi')
                    ->toggle()
                    ->query(fn($query) => $query->needingVideo()),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->recordUrl(fn($record) => Pages\EditEpisode::getUrl([$record]))
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
