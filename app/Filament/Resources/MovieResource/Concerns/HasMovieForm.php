<?php

declare(strict_types=1);

namespace App\Filament\Resources\MovieResource\Concerns;

use App\Enums\AnimeStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

trait HasMovieForm
{
    use HasMovieMediaForm;
    use HasMovieMetadataForm;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make([
                    Section::make('Film Bilgileri')
                        ->icon('heroicon-o-film')
                        ->schema([
                            TextInput::make('title')
                                ->label('Film Adı')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Set $set, ?string $state): void {
                                    if (! $state) {
                                        return;
                                    }
                                    $set('slug', Str::slug($state));
                                })
                                ->maxLength(255),

                            TextInput::make('original_title')
                                ->label('Orijinal Başlık')
                                ->maxLength(255),

                            Textarea::make('overview')
                                ->label('Özet')
                                ->rows(5)
                                ->columnSpanFull(),
                        ]),

                    static::getMediaSection(),
                ])->columnSpan(2),

                Group::make([
                    Section::make('Durum')
                        ->icon('heroicon-o-globe-alt')
                        ->schema([
                            Select::make('status')
                                ->label('Yayın Durumu')
                                ->options(AnimeStatus::class)
                                ->required()
                                ->native(false),

                            TextInput::make('hero_order')
                                ->label('Vitrin Sırası')
                                ->numeric()
                                ->default(0)
                                ->helperText('Slayt sırası (1, 2, 3...). 0: Vitrinde gösterme.'),
                        ]),

                    ...static::getMetadataSections(),
                ])->columnSpan(1),
            ]);
    }
}
