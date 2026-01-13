<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AnimeStatus: string implements HasLabel, HasColor
{
    case ONGOING = 'Devam Ediyor';
    case COMPLETED = 'Tamamlandı';

    public function getLabel(): ?string
    {
        return $this->value;
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ONGOING => 'success',
            self::COMPLETED => 'primary',
        };
    }

    /**
     * Get the badge color for Filament.
     * @deprecated Use getColor() via HasColor interface
     */
    public function color(): string
    {
        return $this->getColor();
    }

    /**
     * Get the display label.
     * @deprecated Use getLabel() via HasLabel interface
     */
    public function label(): string
    {
        return $this->getLabel();
    }
}
