<?php

declare(strict_types=1);

namespace App\Livewire\Anime\Concerns;

use App\Enums\AnimeGenre;
use App\Models\Anime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;

trait HasAnimeFilters
{
    public int $limit = 24;

    public function updatedSearch(): void
    {
        $this->limit = 24;
        $this->resetPage();
    }

    public function updatedGenre(): void
    {
        $this->limit = 24;
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->limit = 24;
        $this->resetPage();
    }

    #[Computed]
    public function animes(): LengthAwarePaginator
    {
        return Anime::query()
            ->select(['id', 'title', 'slug', 'poster_path', 'vote_average', 'release_date', 'genres'])
            ->when($this->search, fn(Builder $query) => $this->applySearchFilter($query))
            ->when($this->genre, fn(Builder $query) => $this->applyGenreFilter($query))
            ->when($this->sort, fn(Builder $query) => $this->applySortOrder($query))
            ->paginate($this->limit);
    }

    public function toggleGenre(string $genreValue): void
    {
        if ($genreValue === 'hepsi') {
            $this->genre = '';
            $this->limit = 24;
            $this->resetPage();
            return;
        }

        $currentGenres = array_filter(explode(',', $this->genre));

        if (in_array($genreValue, $currentGenres)) {
            $currentGenres = array_diff($currentGenres, [$genreValue]);
        } else {
            $currentGenres[] = $genreValue;
        }

        $this->genre = implode(',', $currentGenres);
        $this->limit = 24;
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
        if ($this->genre === 'hepsi') {
            return;
        }

        $genres = explode(',', $this->genre);

        foreach ($genres as $genre) {
            if ($enum = AnimeGenre::tryFrom($genre)) {
                $query->whereJsonContains('genres', $enum->label());
            }
        }
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
