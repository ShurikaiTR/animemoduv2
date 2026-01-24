<div class="pt-8 border-t border-white/5 space-y-10">
    {{-- Tabs --}}
    @include('livewire.anime.partials.comments-tabs', [
        'activeTab' => $activeTab,
        'commentsCount' => $this->counts['comments'],
        'reviewsCount' => $this->counts['reviews'],
        'showReviews' => $this->showReviews
    ])

    {{-- Content Area --}}
    @if(auth()->guest() && $this->items->isEmpty())
        {{-- Scenario 1: Guest + No Items (Merged Box) --}}
        <x-ui.empty-state 
            :icon="$activeTab === 'comments' ? 'heroicon-o-chat-bubble-bottom-center-text' : 'heroicon-o-star'"
            :title="$activeTab === 'comments' ? 'Henüz Yorum Yok' : 'Henüz İnceleme Yok'"
            :description="$activeTab === 'comments' 
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
            'activeTab' => $activeTab,
            'commentForm' => $commentForm,
            'reviewForm' => $reviewForm,
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