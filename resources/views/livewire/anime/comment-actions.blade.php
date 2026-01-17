<div>
    @if($activeTab === 'comments')
        <div
            class="flex items-center bg-white/5 rounded-full p-1 ring-1 ring-white/5 group-hover/comment:ring-white/10 transition-all">
            <button wire:click="toggleLike(true)"
                class="px-3 py-1.5 flex items-center gap-2 rounded-full hover:bg-primary/10 transition-all {{ $isLiked ? 'text-primary bg-primary/10' : 'text-white/40 hover:text-primary' }}">
                <x-heroicon-s-hand-thumb-up class="w-4 h-4" />
                <span class="text-xs font-bold">{{ $likeCount }}</span>
            </button>
            <div class="w-px h-4 bg-white/10 mx-0.5"></div>
            <button wire:click="toggleLike(false)"
                class="px-3 py-1.5 flex items-center gap-2 rounded-full hover:bg-red-500/10 transition-all {{ $isDisliked ? 'text-red-500 bg-red-500/10' : 'text-white/40 hover:text-red-500' }}">
                <x-heroicon-s-hand-thumb-down class="w-4 h-4" />
                <span class="text-xs font-bold">{{ $dislikeCount }}</span>
            </button>
        </div>
    @else
        <div class="flex items-center gap-2 text-white/40">
            <x-heroicon-s-hand-thumb-up class="w-4 h-4" />
            <span class="text-xs">{{ $comment->helpful_count ?? 0 }} kişi bu incelemeyi yararlı buldu</span>
        </div>
    @endif
</div>