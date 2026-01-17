@props(['item', 'activeTab', 'isLiked', 'isDisliked'])

<div class="flex items-center justify-between mt-6 pl-16 relative z-10">
    {{-- Left: Likes (Isolated for Performance) --}}
    <livewire:anime.comment-actions :comment="$item" :activeTab="$activeTab" :key="'actions-' . $item->id" />

    {{-- Right: Actions --}}
    @if($activeTab === 'comments')
        <div class="flex items-center gap-4">
            <button wire:click="toggleReply('{{ $item->id }}')"
                class="flex items-center gap-2 text-white/40 hover:text-primary transition-colors group/action">
                <x-heroicon-s-arrow-turn-down-right class="w-4 h-4 group-hover/action:scale-110 transition-transform" />
                <span class="text-xs font-bold uppercase tracking-wider">Yanıtla</span>
            </button>
            <button wire:click="quote('{{ $item->id }}')"
                class="flex items-center gap-2 text-white/40 hover:text-primary transition-colors group/action">
                <x-heroicon-s-chat-bubble-bottom-center-text
                    class="w-4 h-4 group-hover/action:scale-110 transition-transform" />
                <span class="text-xs font-bold uppercase tracking-wider">Alıntıla</span>
            </button>
        </div>
    @endif
</div>