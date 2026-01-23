<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Enums\AnimeGenre;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layout.app')]
class Discover extends Component
{
    use Concerns\HasAnimeFilters;
    use WithPagination;

    #[Url(as: 'tur')]
    public string $genre = '';

    #[Url(as: 'sirala')]
    public string $sort = 'yeni';

    #[Url(as: 'ara')]
    public string $search = '';

    public function render()
    {
        return view('livewire.anime.discover')
            ->title($this->getPageTitle());
    }

    #[Computed]
    public function pageHeading(): string
    {
        if ($this->search) {
            return '"' . $this->search . '" sonuçları';
        }

        if ($this->genre) {
            $genres = explode(',', $this->genre);
            $labels = [];

            foreach ($genres as $g) {
                if ($enum = AnimeGenre::tryFrom($g)) {
                    $labels[] = $enum->label();
                }
            }

            if (!empty($labels)) {
                return implode(', ', array_slice($labels, 0, 3)) . (count($labels) > 3 ? '...' : '') . ' Animeleri';
            }
        }

        return 'Animeleri Keşfet';
    }

    protected function getPageTitle(): string
    {
        return config('app.name') . ': ' . $this->pageHeading;
    }

    public function loadMore(): void
    {
        $this->limit += 24;
    }

    #[Computed]
    public function availableGenres(): array
    {
        return AnimeGenre::cases();
    }
}
