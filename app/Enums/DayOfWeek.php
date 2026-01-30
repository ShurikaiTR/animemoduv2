<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DayOfWeek: string implements HasLabel
{
    case MONDAY = 'pazartesi';
    case TUESDAY = 'sali';
    case WEDNESDAY = 'carsamba';
    case THURSDAY = 'persembe';
    case FRIDAY = 'cuma';
    case SATURDAY = 'cumartesi';
    case SUNDAY = 'pazar';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MONDAY => 'Pazartesi',
            self::TUESDAY => 'Salı',
            self::WEDNESDAY => 'Çarşamba',
            self::THURSDAY => 'Perşembe',
            self::FRIDAY => 'Cuma',
            self::SATURDAY => 'Cumartesi',
            self::SUNDAY => 'Pazar',
        };
    }

    public static function fromDate(\DateTimeInterface $date): self
    {
        $englishDay = strtolower($date->format('l'));

        return match ($englishDay) {
            'monday' => self::MONDAY,
            'tuesday' => self::TUESDAY,
            'wednesday' => self::WEDNESDAY,
            'thursday' => self::THURSDAY,
            'friday' => self::FRIDAY,
            'saturday' => self::SATURDAY,
            'sunday' => self::SUNDAY,
        };
    }
}
