<?php

declare(strict_types=1);

use App\Enums\CommentTab;
use App\Models\Anime;
use App\Models\Episode;
use App\Services\CommentService;
use App\Livewire\Forms\CommentForm;
use App\Livewire\Forms\ReviewForm;
use App\Livewire\Anime\Concerns\InteractsWithComments;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Lazy;
use Livewire\Component;

new #[Lazy] class extends Component
{
    use InteractsWithComments;

    public function placeholder(): View
    {
        return view('livewire.anime.partials.comments-skeleton');
    }

    #[On('comment-added')]
    public function refresh(): void
    {
    }

    #[Locked]
    public Anime $anime;

    #[Locked]
    public ?Episode $episode = null;

    public string $activeTab = CommentTab::COMMENTS->value;

    public CommentForm $commentForm;

    public ReviewForm $reviewForm;

    /** @var array<string, string> */
    public array $replyContent = [];

    public int $perPage = 10;

    /** @var array<string, bool> */
    public array $showReplyInput = [];

    /** @var array<string, bool> */
    public array $revealedSpoilers = [];

    public function mount(Anime $anime, ?Episode $episode = null): void
    {
        $this->anime = $anime;
        $this->episode = $episode;

        if ($this->episode?->exists) {
            $this->activeTab = CommentTab::COMMENTS->value;
        }
    }

    public function setTab(string $tab): void
    {
        if ($this->episode?->exists && $tab === CommentTab::REVIEWS->value) {
            return;
        }

        $this->activeTab = $tab;
        $this->perPage = 10;
        $this->resetValidation();
        $this->commentForm->resetFields();
        $this->reviewForm->resetFields();
        $this->showReplyInput = [];
    }

    public function loadMore(): void
    {
        $this->perPage += 10;
    }

    #[Computed]
    public function items()
    {
        return app(CommentService::class)->getItems([
            'anime_id' => $this->anime->id,
            'episode_id' => $this->episode?->id,
            'perPage' => $this->perPage,
            'activeTab' => $this->activeTab,
        ]);
    }

    #[Computed]
    public function counts()
    {
        return app(CommentService::class)->getCounts($this->anime->id, $this->episode?->id);
    }

    #[Computed]
    public function hasMore(): bool
    {
        $total = ($this->activeTab === CommentTab::COMMENTS->value ? $this->counts['comments'] : $this->counts['reviews']);
        return $this->items->count() < $total;
    }

    #[Computed]
    public function showReviews(): bool
    {
        return empty($this->episode?->id);
    }
}; ?>

<div class="pt-8 border-t border-white/5 space-y-10">
    {{-- Tabs --}}
    @include('livewire.anime.partials.comments-tabs', [
        'activeTab' => $this->activeTab,
        'commentsCount' => $this->counts['comments'],
        'reviewsCount' => $this->counts['reviews'],
        'showReviews' => $this->showReviews
    ])

    {{-- Content Area --}}
    @if(auth()->guest() && $this->items->isEmpty())
        {{-- Scenario 1: Guest + No Items (Merged Box) --}}
        <x-ui.empty-state 
            :icon="$this->activeTab === 'comments' ? 'heroicon-o-chat-bubble-bottom-center-text' : 'heroicon-o-star'"
            :title="$this->activeTab === 'comments' ? 'Henüz Yorum Yok' : 'Henüz İnceleme Yok'"
            :description="$this->activeTab === 'comments' 
                ? 'Sahne senin. Giriş yap ve ilk yorumu sen yaz.' 
                : 'Bu animeyi inceleyen ilk kişi sen ol! Görüşlerini paylaşmak için hemen giriş yap.'"
        >
            <x-ui.button @click="$dispatch('openAuthModal')" variant="primary" size="lg" class="px-10 font-black">
                GİRİŞ YAP
            </x-ui.button>
        </x-ui.empty-state>
    @else
        {{-- Input Section (Scenario 2 & 3) --}}
        @include('livewire.anime.partials.comments-input', [
            'activeTab' => $this->activeTab,
            'commentForm' => $this->commentForm,
            'reviewForm' => $this->reviewForm,
        ])

        {{-- Items List --}}
        <ul class="block" wire:transition>
            @forelse($this->items as $item)
                <div wire:key="comment-{{ $item->id }}">
                    @include('livewire.anime.partials.comment-item', ['item' => $item, 'isReply' => false])
                </div>
            @empty
                {{-- Scenario 2: Auth + No Items --}}
                <x-ui.empty-state 
                    icon="heroicon-o-sparkles"
                    title="Buralar Henüz Dutluk :)"
                    description="Kimse gelmeden ilk yorumu sen yaz, yerini kap!"
                    class="py-10"
                />
            @endforelse

            @if($this->hasMore)
                <div class="flex justify-center pt-6">
                    <x-ui.button wire:click="loadMore" variant="ghost" class="text-primary hover:text-white group">
                        Daha Fazla Yükle
                        <x-heroicon-o-chevron-down class="w-4 h-4 ml-2 group-hover:translate-y-1 transition-transform" />
                    </x-ui.button>
                </div>
            @endif
        </ul>
    @endif
</div>