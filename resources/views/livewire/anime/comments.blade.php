<div class="pt-8 border-t border-white/5 space-y-10">
    {{-- Tabs --}}
    @include('livewire.anime.partials.comments-tabs', [
        'activeTab' => $activeTab,
        'commentsCount' => $commentsCount,
        'reviewsCount' => $reviewsCount,
        'showReviews' => $showReviews
    ])

    {{-- Input Section --}}
    @include('livewire.anime.partials.comments-input', [
        'activeTab' => $activeTab,
        'title' => $title,
        'rating' => $rating,
        'content' => $content,
        'isSpoiler' => $isSpoiler,
        'message' => $errors->first('content')
    ])

    {{-- Items List --}}
    <ul class="block">
        @forelse($items as $item)
            @include('livewire.anime.partials.comment-item', ['item' => $item, 'isReply' => false])
        @empty
            @include('livewire.anime.partials.comments-empty-state', ['activeTab' => $activeTab])
        @endforelse

        @if($hasMore)
            <div class="flex justify-center pt-6">
                <x-ui.button wire:click="loadMore" variant="ghost" class="text-primary hover:text-white group">
                    Daha Fazla Yükle
                    <x-heroicon-o-chevron-down class="w-4 h-4 ml-2 group-hover:translate-y-1 transition-transform" />
                </x-ui.button>
            </div>
        @endif
    </ul>
</div>