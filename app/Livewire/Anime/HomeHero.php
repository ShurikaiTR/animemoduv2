<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Models\Anime;
use Illuminate\View\View;
use Livewire\Component;

class HomeHero extends Component
{
    public function render(): View
    {
        $featuredAnime = Anime::where('is_featured', true)
            ->latest()
            ->first();

        return view('livewire.anime.home-hero', [
            'featuredAnime' => $featuredAnime,
        ]);
    }
}
