<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Enums\AnimeGenre;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithPagination;

#[Lazy]
#[Layout('components.layout.app')]
class Discover extends Component
{
    use Concerns\HasAnimeFilters;
    use WithPagination;


    #[Computed]
    public function pageHeading(): string
    {
        if ($this->search) {
            return '"' . $this->search . '" sonuçları';
        }

        if (!empty($this->genres)) {
            $labels = [];

            foreach ($this->genres as $g) {
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

    public function getPageTitle(): string
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
