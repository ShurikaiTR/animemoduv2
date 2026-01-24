<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Models\Anime;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Lazy]
#[Layout('components.layout.app')]
class Show extends Component
{
    #[Locked]
    public ?Anime $anime = null;

    public function placeholder(): View
    {
        return view('livewire.anime.partials.show-skeleton');
    }

    public function mount(string $slug): void
    {
        $this->anime = Cache::remember("anime_show_{$slug}", 86400, function () use ($slug) {
            return Anime::where('slug', $slug)->firstOrFail();
        });
    }

    #[Computed]
    public function characters(): array
    {
        return $this->anime?->characters ?? [];
    }

    #[Computed]
    public function trailer(): ?string
    {
        return $this->anime?->trailer_key;
    }

    public function render()
    {
        /** @var \Livewire\Features\SupportPageComponents\View $view */
        $view = view('livewire.anime.show');

        return $view->title($this->getPageTitle());
    }

    protected function getPageTitle(): string
    {
        return ($this->anime?->title ?? 'Anime') . ' - ' . config('app.name');
    }
}
