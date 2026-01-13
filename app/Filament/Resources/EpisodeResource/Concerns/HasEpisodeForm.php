<?php

declare(strict_types=1);

namespace App\Filament\Resources\EpisodeResource\Concerns;

use Filament\Forms;
use Filament\Schemas\Schema;

trait HasEpisodeForm
{
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('anime_id')
                    ->relationship('anime', 'title')
                    ->required(),
                Forms\Components\TextInput::make('tmdb_id')
                    ->numeric(),
                Forms\Components\TextInput::make('title'),
                Forms\Components\Textarea::make('overview')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('still_path'),
                Forms\Components\TextInput::make('vote_average')
                    ->numeric(),
                Forms\Components\DatePicker::make('air_date'),
                Forms\Components\TextInput::make('season_number')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('episode_number')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('absolute_episode_number')
                    ->numeric(),
                Forms\Components\TextInput::make('duration')
                    ->numeric(),
                Forms\Components\TextInput::make('video_url'),
            ]);
    }
}
