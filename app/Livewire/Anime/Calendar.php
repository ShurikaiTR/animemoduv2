<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Actions\Anime\GetWeeklyScheduleAction;
use App\Enums\DayOfWeek;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
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

    #[Computed]
    public function schedule(): Collection
    {
        return app(GetWeeklyScheduleAction::class)();
    }

    #[Computed]
    public function animes(): Collection
    {
        $animes = $this->schedule->get($this->activeDay, collect());

        if ($this->showOnlyWatchlist && auth()->check()) {
            $watchlistIds = auth()->user()->watchlist()->pluck('anime_id')->toArray();
            $animes = $animes->filter(fn ($anime) => in_array($anime->id, $watchlistIds));
        }

        return $animes;
    }

    #[Layout('components.layout.app')]
    #[Title('Anime Yayın Takvimi')]
    public function render(): View
    {
        return view('livewire.anime.calendar', [
            'animes' => $this->animes,
            'days' => DayOfWeek::cases(),
        ]);
    }

    public function setDay(string $day): void
    {
        $this->activeDay = $day;
    }
}
