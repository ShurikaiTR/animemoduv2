<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Enums\AnimeStatus;
use App\Models\Anime;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

#[Lazy]
#[Layout('components.layout.app')]
#[Title('Animeler - AnimeModu')]
class Hub extends Component
{
    use \Livewire\WithPagination;

    public function placeholder()
    {
        return view('livewire.anime.hub-skeleton');
    }

    #[\Livewire\Attributes\Url(as: 'harf')]
    public string $letter = '';

    public function updatedLetter(): void
    {
        $this->page = 1;
        $this->resetPage();
    }




    public int $page = 1;

    public function loadMore(): void
    {
        $this->page++;
    }

    #[Computed]
    public function animes()
    {
        $cacheKey = 'hub_list_' . ($this->letter ?: 'vitrin') . '_page_' . $this->page;

        return Cache::remember($cacheKey, 3600, function () {
            $data = Anime::query()
                ->select(['id', 'title', 'slug', 'poster_path', 'vote_average', 'release_date', 'genres'])
                ->where('media_type', 'tv')
                ->when($this->letter, function ($query) {
                    if ($this->letter === '#') {
                        $query->where(function ($q) {
                            foreach (range(0, 9) as $i) {
                                $q->orWhere('title', 'like', $i . '%');
                            }
                        });
                    } else {
                        $query->where('title', 'like', $this->letter . '%');
                    }
                    $query->orderBy('title');
                }, function ($query) {
                    $query->orderByDesc('updated_at');
                })
                ->forPage($this->page, 24)
                ->get();

            return $data;
        });
    }

    #[Computed]
    public function totalCount(): int
    {
        $cacheKey = 'hub_total_' . ($this->letter ?: 'vitrin');

        return Cache::remember($cacheKey, 3600, function () {
            return Anime::query()
                ->where('media_type', 'tv')
                ->when($this->letter, function ($query) {
                    if ($this->letter === '#') {
                        $query->where(function ($q) {
                            foreach (range(0, 9) as $i) {
                                $q->orWhere('title', 'like', $i . '%');
                            }
                        });
                    } else {
                        $query->where('title', 'like', $this->letter . '%');
                    }
                })
                ->count();
        });
    }

    public function hasMorePages(): bool
    {
        return $this->page * 24 < $this->totalCount;
    }

    public function render()
    {
        return view('livewire.anime.hub');
    }
}
