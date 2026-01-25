<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Actions\Anime\GetWeeklyScheduleAction;
use App\Enums\DayOfWeek;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

class Calendar extends Component
{
    #[Url(as: 'gun')]
    public string $activeDay;

    #[Url(as: 'takip')]
    public bool $showOnlyWatchlist = false;

    public function mount(): void
    {
        $this->activeDay = $this->activeDay ?? DayOfWeek::fromDate(now())->value;
    }

    #[Layout('components.layout.app')]
    #[Title('Anime Yayın Takvimi')]
    public function render(GetWeeklyScheduleAction $getWeeklySchedule): View
    {
        $schedule = $getWeeklySchedule();

        $animes = $schedule->get($this->activeDay, collect());

        if ($this->showOnlyWatchlist && auth()->check()) {
            // Watchlist relationship logic would go here
            // For now, let's assume a basic placeholder filtering
            // $animes = $animes->filter(fn ($anime) => auth()->user()->isFollowing($anime));
        }

        return view('livewire.anime.calendar', [
            'animes' => $animes,
            'days' => DayOfWeek::cases(),
        ]);
    }

    public function setDay(string $day): void
    {
        $this->activeDay = $day;
    }
}
