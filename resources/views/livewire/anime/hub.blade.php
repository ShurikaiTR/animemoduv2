<?php

declare(strict_types=1);

use App\Models\Anime;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new #[Lazy]
#[Layout('components.layout.app')]
#[Title('Animeler - AnimeModu')]
class extends Component
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

    #[Computed(cache: true, seconds: 3600)]
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
}; ?>

<div class="min-h-screen pb-20 bg-bg-main">
    <x-slot:title>
        {{ $this->letter ? "\"{$this->letter}\" İle Başlayan Animeler" : 'Tüm Animeler' }} - AnimeModu
    </x-slot:title>

    <div class="container mx-auto px-4 md:px-6 pt-24 md:pt-32 pb-10 font-rubik">
        {{-- A-Z Filter Bar --}}
        <div class="mb-8 overflow-x-auto pb-2 scrollbar-hide">
            <div class="flex items-center gap-1 min-w-max">
                <button wire:click="$set('letter', '')"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ empty($this->letter) ? 'bg-primary text-white' : 'bg-bg-secondary text-text-main hover:text-white hover:bg-bg-secondary/80' }}">
                    VİTRİN
                </button>
                <div class="w-px h-6 bg-white/10 mx-2"></div>
                <button wire:click="$set('letter', '#')"
                    class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-bold transition-all {{ $this->letter === '#' ? 'bg-primary text-white' : 'bg-bg-secondary text-text-main hover:text-white hover:bg-bg-secondary/80' }}">
                    #
                </button>
                @foreach(range('A', 'Z') as $char)
                    <button wire:click="$set('letter', '{{ $char }}')"
                        class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-bold transition-all {{ $this->letter === $char ? 'bg-primary text-white' : 'bg-bg-secondary text-text-main hover:text-white hover:bg-bg-secondary/80' }}">
                        {{ $char }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Grid Content --}}
        <div class="animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="flex items-center justify-between mb-6">
                <div>
                    @if($this->letter)
                        <h2 class="text-2xl font-bold text-white font-rubik">"{{ $this->letter }}" İle Başlayanlar</h2>
                    @else
                        <h2 class="text-2xl font-bold text-white font-rubik">Tüm Animeler</h2>
                    @endif
                    <p class="text-text-main/60 text-sm mt-1">Toplam <span
                            class="text-primary font-bold">{{ $this->animes->total() }}</span> içerik
                        listeleniyor</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @forelse($this->animes as $anime)
                    <x-anime-card :anime="$anime" wire:key="hub-{{ $anime->id }}" />
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl text-white/20 font-bold">{{ $this->letter ?: '?' }}</span>
                        </div>
                        <h3 class="text-white font-bold mb-2">Sonuç Bulunamadı</h3>
                        <p class="text-text-main/60 text-sm">Aradığınız kriterlere uygun içerik bulunamadı.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                @if($this->animes->hasMorePages())
                    <div x-intersect.full="$wire.loadMore()">
                        {{-- Loading State (Skeletons) --}}
                        <div wire:loading wire:target="loadMore" wire:loading.delay
                            class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 w-full mt-4">
                            @foreach(range(1, 12) as $i)
                                <div class="aspect-[2/3] w-full rounded-xl overflow-hidden">
                                    <div class="w-full h-full bg-white/5 animate-pulse"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>