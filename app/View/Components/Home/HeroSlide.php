<?php

declare(strict_types=1);

namespace App\View\Components\Home;

use App\Services\TmdbService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HeroSlide extends Component
{
    public ?string $backdropUrl;
    public ?string $backdropW780;
    public ?string $backdropW1280;
    public ?string $logoUrl;

    public function __construct(
        public object $anime,
        public int $index,
        public string $activeSlide,
        protected TmdbService $tmdbService
    ) {
        $this->prepareImages();
    }

    protected function prepareImages(): void
    {
        $this->backdropUrl = $this->anime->backdrop_path
            ? $this->tmdbService->getImageUrl($this->anime->backdrop_path, 'w1280')
            : asset('img/placeholder-backdrop.jpg');

        $this->backdropW780 = $this->anime->backdrop_path
            ? $this->tmdbService->getImageUrl($this->anime->backdrop_path, 'w780')
            : null;

        $this->backdropW1280 = $this->anime->backdrop_path
            ? $this->tmdbService->getImageUrl($this->anime->backdrop_path, 'w1280')
            : null;

        $this->logoUrl = $this->anime->logo_path
            ? $this->tmdbService->getImageUrl($this->anime->logo_path, 'w500')
            : null;
    }

    public function render(): View|Closure|string
    {
        return view('components.home.hero-slide');
    }
}
