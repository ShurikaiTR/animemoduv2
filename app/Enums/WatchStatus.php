<?php

declare(strict_types=1);

namespace App\Enums;

enum WatchStatus: string
{
    case WATCHING = 'watching';
    case COMPLETED = 'completed';
    case ON_HOLD = 'on_hold';
    case DROPPED = 'dropped';
    case PLAN_TO_WATCH = 'plan_to_watch';

    public function label(): string
    {
        return match ($this) {
            self::WATCHING => 'İzleniyor',
            self::COMPLETED => 'Tamamlandı',
            self::ON_HOLD => 'Durduruldu',
            self::DROPPED => 'Bırakıldı',
            self::PLAN_TO_WATCH => 'Planlandı',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::WATCHING => 'success',
            self::COMPLETED => 'info',
            self::ON_HOLD => 'warning',
            self::DROPPED => 'danger',
            self::PLAN_TO_WATCH => 'gray',
        };
    }
}
