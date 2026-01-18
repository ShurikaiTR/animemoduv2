<?php

declare(strict_types=1);

namespace App\Filament\Resources\AnimeResource\Concerns;

use App\Enums\AnimeStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

trait HasAnimeForm
{
    use HasAnimeMediaForm;
    use HasAnimeMetadataForm;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                // MAIN CONTENT (Left - Span 2)
                Grid::make(1)
                    ->columnSpan(['lg' => 2])
                    ->components([
                        Section::make('İçerik Bilgileri')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Başlık')
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

                                TagsInput::make('characters')
                                    ->label('Karakterler')
                                    ->columnSpanFull(),
                            ]),

                        static::getMediaSection(),
                    ]),

                // SIDEBAR (Right - Span 1)
                Grid::make(1)
                    ->columnSpan(['lg' => 1])
                    ->components([
                        Section::make('Yayın Durumu')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Select::make('status')
                                    ->label('Durum')
                                    ->options(AnimeStatus::class)
                                    ->required()
                                    ->native(false),

                                TextInput::make('hero_order')
                                    ->label('Vitrin Sırası')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Slayt sırası (1, 2, 3...). 0: Vitrinde gösterme.'),
                            ]),

                        ...static::getMetadataSection(),
                    ]),
            ]);
    }
}
