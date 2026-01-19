<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Models\Anime;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Show extends Component
{
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

    #[Layout('components.layout.app')]
    public function render(): View
    {
        return view('livewire.anime.show', [
            'anime' => $this->anime,
            'characters' => $this->anime?->characters ?? [],
            'trailer' => $this->anime?->trailer_key,
        ]);
    }
}
