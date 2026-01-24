<?php

declare(strict_types=1);

namespace App\Livewire\Anime\Concerns;

use App\Enums\AnimeGenre;
use App\Models\Anime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

trait HasAnimeFilters
{
    public int $limit = 24;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'tur', except: [])]
    public array $genres = [];

    #[Url(as: 'sirala', except: 'created_at')]
    public string $sort = 'created_at';

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->dispatch('scroll-to-top');
    }

    public function updatedGenres(): void
    {
        $this->resetPage();
        $this->dispatch('scroll-to-top');
    }

    public function updatedSort(): void
    {
        $this->resetPage();
        $this->dispatch('scroll-to-top');
    }

    #[Computed]
    public function animes(): LengthAwarePaginator
    {
        return Anime::query()
            ->select(['id', 'title', 'slug', 'poster_path', 'vote_average', 'release_date', 'genres'])
            ->when($this->search, fn(Builder $q) => $this->applySearchFilter($q))
            ->when(!empty($this->genres), fn(Builder $q) => $this->applyGenreFilter($q))
            ->when($this->sort, fn(Builder $q) => $this->applySortOrder($q))
            ->paginate($this->limit);
    }

    public function resetFilters(): void
    {
        $this->reset(['genres', 'search', 'sort']);
        $this->resetPage();
    }

    public function toggleGenre(string $genreValue): void
    {
        if ($genreValue === 'hepsi') {
            $this->genres = [];
            $this->resetPage();
            return;
        }

        if (in_array($genreValue, $this->genres)) {
            $this->genres = array_diff($this->genres, [$genreValue]);
        } else {
            $this->genres[] = $genreValue;
        }

        $this->genres = array_values($this->genres);
        $this->resetPage();
    }

    protected function applySearchFilter(Builder $query): void
    {
        $query->where(function (Builder $q) {
            $terms = [$this->search];

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
    }

    protected function applyGenreFilter(Builder $query): void
    {
        $query->where(function (Builder $q) {
            foreach ($this->genres as $genre) {
                if ($enum = AnimeGenre::tryFrom($genre)) {
                    $q->orWhereJsonContains('genres', $enum->label());
                }
            }
        });
    }

    protected function applySortOrder(Builder $query): void
    {
        match ($this->sort) {
            'puan' => $query->orderByDesc('vote_average'),
            'populer' => $query->orderByDesc('vote_count'),
            'eski' => $query->orderBy('release_date'),
            'yeni' => $query->orderByDesc('release_date'),
            default => $query->orderByDesc('created_at'),
        };
    }
}
