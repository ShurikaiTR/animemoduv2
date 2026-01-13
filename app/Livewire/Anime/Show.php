<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Models\Anime;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    public Anime $anime;

    public function mount(string $slug): void
    {
        $this->anime = Anime::where('slug', $slug)->firstOrFail();
    }

    #[Layout('components.layout.app')]
    public function render()
    {
        return view('livewire.anime.show', [
            'anime' => $this->anime,
            'characters' => $this->anime->characters ?? [],
            'trailer' => $this->anime->trailer_key,
        ]);
    }
}
