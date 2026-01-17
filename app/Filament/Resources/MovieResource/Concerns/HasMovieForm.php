<?php

declare(strict_types=1);

namespace App\Filament\Resources\MovieResource\Concerns;

use App\Enums\AnimeStatus;
use App\Models\Genre;
use App\Services\TmdbService;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
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
                                    if (!$state) {
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

                            Toggle::make('is_featured')
                                ->label('Vitrin (Hero) İçeriği')
                                ->onIcon('heroicon-m-star')
                                ->onColor('warning'),
                        ]),

                    ...static::getMetadataSections(),
                ])->columnSpan(1),
            ]);
    }
}
