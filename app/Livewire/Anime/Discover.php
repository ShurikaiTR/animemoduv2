<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Enums\AnimeGenre;
use App\Models\Anime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layout.app')]
class Discover extends Component
{
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

    protected function getPageTitle(): string
    {
        if ($this->search) {
            return '"' . $this->search . '" için sonuçlar - AnimeModu';
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
                return implode(', ', array_slice($labels, 0, 3)) . (count($labels) > 3 ? '...' : '') . ' Animeleri - AnimeModu';
            }
        }

        return 'Keşfet - Yeni ve Popüler Animeler - AnimeModu';
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedGenre(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function animes(): LengthAwarePaginator
    {
        return Anime::query()
            ->select(['id', 'title', 'slug', 'poster_path', 'vote_average', 'release_date', 'genres'])
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $terms = [$this->search];

                    // Handle "x" vs "×" variations (common in anime titles like SPY×FAMILY, Hunter×Hunter)
                    if (str_contains($this->search, 'x')) {
                        $terms[] = str_replace('x', '×', $this->search);
                    }
                    if (str_contains($this->search, '×')) {
                        $terms[] = str_replace('×', 'x', $this->search);
                    }

                    foreach ($terms as $term) {
                        $q->orWhere('title', 'like', '%' . $term . '%')
                            ->orWhere('original_title', 'like', '%' . $term . '%');
                    }
                });
            })
            ->when($this->genre, function (Builder $query) {
                // Legacy support: 'hepsi' means no filter
                if ($this->genre === 'hepsi') {
                    return;
                }

                // Allow multiple genres separated by comma
                $genres = explode(',', $this->genre);

                foreach ($genres as $genre) {
                    // Check if genre exists in Enum to prevent SQL injection or bad queries
                    if ($enum = AnimeGenre::tryFrom($genre)) {
                        $query->whereJsonContains('genres', $enum->label());
                    }
                }
            })
            ->when($this->sort, function (Builder $query) {
                match ($this->sort) {
                    'puan' => $query->orderByDesc('vote_average'),
                    'populer' => $query->orderByDesc('vote_count'), // Assuming vote_count exists, otherwise vote_average
                    'eski' => $query->orderBy('release_date'),
                    'yeni' => $query->orderByDesc('release_date'),
                    default => $query->orderByDesc('created_at'),
                };
            })
            ->paginate(20);
    }

    /**
     * Get available genres for the sidebar
     */
    #[Computed]
    public function availableGenres(): array
    {
        return AnimeGenre::cases();
    }

    public function toggleGenre(string $genreValue): void
    {
        if ($genreValue === 'hepsi') {
            $this->genre = '';
            return;
        }

        $currentGenres = array_filter(explode(',', $this->genre));

        if (in_array($genreValue, $currentGenres)) {
            $currentGenres = array_diff($currentGenres, [$genreValue]);
        } else {
            $currentGenres[] = $genreValue;
        }

        $this->genre = implode(',', $currentGenres);
        $this->resetPage();
    }
}
