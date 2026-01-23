<?php

declare(strict_types=1);

namespace App\Filament\Resources\AnimeResource\Concerns;

use App\Enums\AnimeStatus;
use App\Models\Genre;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                    ->extraImgAttributes(['class' => 'rounded shadow-sm border border-gray-100 dark:border-gray-800'])
                    ->defaultImageUrl(asset('img/placeholder.jpg')),
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->color('primary')
                    ->description(fn($record) => $record->original_title)
                    ->wrap(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('genres')
                    ->label('Türler')
                    ->badge()
                    ->separator(',')
                    ->limitList(2)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('vote_average')
                    ->label('Puan')
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-m-star')
                    ->copyable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hero_order')
                    ->label('Vitrin')
                    ->badge()
                    ->color(fn($record) => $record->hero_order > 0 ? 'warning' : 'gray')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('episodes_count')
                    ->label('Bölüm')
                    ->counts('episodes')
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-m-play-circle')
                    ->sortable(),
                Tables\Columns\TextColumn::make('release_date')
                    ->label('Yıl')
                    ->date('Y')
                    ->sortable()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('tmdb_id')
                    ->label('TMDB')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Eklenme')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options(AnimeStatus::class)
                    ->multiple(),
                Tables\Filters\SelectFilter::make('genres')
                    ->label('Tür')
                    ->options(Genre::all()->pluck('name', 'name'))
                    ->multiple()
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['values'], function (Builder $query, $values) {
                            return $query->where(function (Builder $query) use ($values) {
                                foreach ($values as $value) {
                                    $query->orWhereJsonContains('genres', $value);
                                }
                            });
                        });
                    }),
                Tables\Filters\TernaryFilter::make('hero_order')
                    ->label('Vitrin')
                    ->placeholder('Hepsi')
                    ->trueLabel('Vitrindekiler')
                    ->falseLabel('Vitrin Olmayanlar')
                    ->queries(
                        true: fn(Builder $query) => $query->where('hero_order', '>', 0),
                        false: fn(Builder $query) => $query->where('hero_order', 0),
                        blank: fn(Builder $query) => $query,
                    ),
            ])
            ->actions([
                ViewAction::make()
                    ->iconButton(),
                EditAction::make()
                    ->iconButton(),
                DeleteAction::make()
                    ->iconButton(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
