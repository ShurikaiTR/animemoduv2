<?php

declare(strict_types=1);

namespace App\Filament\Resources\AnimeResource\Concerns;

use App\Enums\AnimeStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Illuminate\Support\Str;

trait HasAnimeForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tmdb_id')
                    ->numeric(),
                Forms\Components\TextInput::make('anilist_id')
                    ->numeric(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        if (!$state) {
                            return;
                        }

                        $slug = str_replace('×', 'x', $state);
                        $set('slug', Str::slug($slug));
                    }),
                Forms\Components\TextInput::make('original_title'),
                Forms\Components\Textarea::make('overview')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('poster_path'),
                Forms\Components\TextInput::make('backdrop_path'),
                Forms\Components\TextInput::make('vote_average')
                    ->numeric(),
                Forms\Components\DatePicker::make('release_date'),
                Forms\Components\TextInput::make('slug')
                    ->required(),
                Forms\Components\Select::make('media_type')
                    ->options([
                        'tv' => 'TV Serisi',
                        'movie' => 'Film',
                    ])
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options(AnimeStatus::class)
                    ->required(),
                Forms\Components\Select::make('structure_type')
                    ->label('Bölüm Yapısı')
                    ->options([
                        'seasonal' => 'Sezonluk (S1 E1)',
                        'absolute' => 'Absolute (Bölüm 100)',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('genres')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('characters')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_featured')
                    ->required(),
                Forms\Components\TextInput::make('vote_count')
                    ->numeric(),
                Forms\Components\TextInput::make('trailer_key'),
            ]);
    }
}
