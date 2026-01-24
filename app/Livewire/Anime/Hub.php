<?php

declare(strict_types=1);

namespace App\Livewire\Anime;

use App\Models\Anime;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Lazy]
#[Layout('components.layout.app')]
#[Title('Animeler - AnimeModu')]
class Hub extends Component
{
    use WithPagination;

    public function placeholder()
    {
        return view('livewire.anime.hub-skeleton');
    }

    #[Url(as: 'harf')]
    public string $letter = '';

    public int $limit = 24;

    public function updatedLetter(): void
    {
        $this->limit = 24;
        $this->resetPage();
    }

    public function loadMore(): void
    {
        $this->limit += 24;
    }

    #[Computed]
    public function animes()
    {
        return Anime::query()
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
            ->paginate($this->limit);
    }
}
