@props(['item', 'activeTab' => 'comments', 'isReply' => false, 'revealedSpoilers' => []])

@php
    $profile = $item->user->profile;
    $isAdmin = $profile->role === 'admin';
    $hasRevealed = isset($revealedSpoilers[$item->id]);

    $isLiked = false;
    $isDisliked = false;

    if ($item->relationLoaded('likes')) {
        $userLike = $item->likes->first();
        $isLiked = $userLike && $userLike->is_like;
        $isDisliked = $userLike && !$userLike->is_like;
    }
@endphp

<li
    class="relative group/comment p-6 rounded-3xl transition-all duration-300 mb-6 overflow-hidden 
    {{ $isReply ? 'ml-6 md:ml-10 border-l-2 border-l-primary/20 bg-bg-secondary/40 hover:bg-bg-secondary/60 backdrop-blur-sm border border-white/5' : '' }} 
    {{ !$isReply && $item->is_pinned
    ? 'bg-primary/5 border border-primary/20 shadow-glow-subtle'
    : (!$isReply ? 'bg-bg-secondary/40 hover:bg-bg-secondary/60 backdrop-blur-sm border border-white/5 hover:border-white/10' : '') }}">

    @if(!$isReply && $item->is_pinned)
        <div class="flex items-center gap-2 mb-4 text-primary border-b border-primary/10 pb-3">
            <x-heroicon-s-bookmark class="w-4 h-4" />
            <span class="text-xs font-black uppercase tracking-widest">SABİTLENMİŞ YORUM</span>
        </div>
    @endif

    {{-- Author Header & Meta --}}
    <div class="flex items-start justify-between gap-4 mb-4 relative z-10">
        <div class="flex items-center gap-4">
            {{-- Avatar --}}
            <div class="relative group/avatar">
                <img src="{{ $profile->avatar_url }}" alt="{{ $profile->username }}"
                    class="w-12 h-12 rounded-full object-cover ring-2 ring-white/5 group-hover/avatar:ring-primary/50 transition-all shadow-lg">
            </div>

            {{-- User Info --}}
            <div class="flex flex-col">
                <div class="flex items-center gap-2">
                    <span
                        class="text-white font-bold text-base tracking-wide group-hover/comment:text-primary transition-colors">
                        {{ $profile->username }}
                    </span>
                    @if($isAdmin)
                        <span
                            class="flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-primary/10 border border-primary/20 text-[10px] font-bold text-primary uppercase tracking-wider">
                            <x-heroicon-s-shield-check class="w-3 h-3" />
                            Yönetici
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2 text-xs text-white/40">
                <span>{{ $item->created_at->diffForHumans() }}</span>
                {{-- Only show simple dot separator for comments, reviews get their own big display --}}
                @if($activeTab !== 'reviews')
                    <span class="w-1 h-1 rounded-full bg-white/20"></span>
                @endif
            </div>
        </div>
    </div>

    {{-- Review Rating Badge (Top Right) --}}
    @if($activeTab === 'reviews' && $item->rating)
        <div
            class="absolute top-6 right-6 flex items-center gap-2 bg-black/20 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/5 shadow-lg">
            <x-heroicon-s-star class="w-6 h-6 text-primary drop-shadow-glow" />
            <div class="flex items-baseline gap-0.5">
                <span class="text-xl font-black text-white leading-none">{{ $item->rating }}</span>
                <span class="text-[10px] font-bold text-white/40">/10</span>
            </div>
        </div>
    @else
        {{-- Standard Actions for Comments --}}
        <div class="flex items-center gap-2 opacity-0 group-hover/comment:opacity-100 transition-opacity">
            @if(!$isReply && auth()->check() && auth()->user()->profile->role === 'admin')
                <button wire:click="pinComment('{{ $item->id }}')"
                    class="p-2 rounded-lg hover:bg-white/10 text-white/40 hover:text-white transition-all {{ $item->is_pinned ? '!text-primary opacity-100' : '' }}"
                    title="{{ $item->is_pinned ? 'Sabitlemeyi Kaldır' : 'Sabitle' }}">
                    @if($item->is_pinned)
                        <x-heroicon-s-bookmark class="w-4 h-4" />
                    @else
                        <x-heroicon-o-bookmark class="w-4 h-4" />
                    @endif
                </button>
            @endif
        </div>
    @endif


    {{-- Content --}}
    <div class="pl-16 relative z-10">
        @if($activeTab === 'reviews' && $item->title)
            <div class="mb-3">
                <h5 class="font-black text-xl text-white leading-tight tracking-tight relative inline-block">
                    {{ $item->title }}
                    <div class="absolute -bottom-1 left-0 w-1/3 h-0.5 bg-primary/50 rounded-full"></div>
                </h5>
            </div>
        @endif

        @if($item->is_spoiler && !$hasRevealed)
            <div class="rounded-xl overflow-hidden relative group/spoiler cursor-pointer"
                wire:click="revealSpoiler('{{ $item->id }}')">
                <div
                    class="absolute inset-0 bg-white/5 backdrop-blur-md z-20 flex flex-col items-center justify-center gap-3 transition-all duration-300 group-hover/spoiler:bg-white/10">
                    <x-heroicon-o-eye-slash
                        class="w-8 h-8 text-white/50 group-hover/spoiler:text-primary transition-colors" />
                    <span class="text-sm font-bold text-white/50 tracking-wider">SPOILER İÇERİK</span>
                    <span class="text-xs text-white/30">Görmek için tıklayın</span>
                </div>
                <div class="blur-sm opacity-20 p-4 select-none pointer-events-none">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua.
                </div>
            </div>
        @else
            <div class="text-white/80 text-[15px] leading-7 font-normal">
                @php
                    $content = e($item->content);
                    $content = preg_replace(
                        '/\[quote\](.*?)\[\/quote\]/s',
                        '<div class="my-4 pl-4 border-l-4 border-primary/50 bg-white/5 rounded-r-lg p-4 text-white/60 italic text-sm">$1</div>',
                        $content
                    );
                @endphp
                {!! nl2br($content) !!}
            </div>
        @endif
    </div>

    {{-- Bottom Actions --}}
    <div class="flex items-center justify-between mt-6 pl-16 relative z-10">
        {{-- Left: Likes --}}
        <div>
            @if($activeTab === 'comments')
                <div
                    class="flex items-center bg-white/5 rounded-full p-1 ring-1 ring-white/5 group-hover/comment:ring-white/10 transition-all">
                    <button wire:click="toggleLike('{{ $item->id }}', true)"
                        class="px-3 py-1.5 flex items-center gap-2 rounded-full hover:bg-primary/10 transition-all {{ $isLiked ? 'text-primary bg-primary/10' : 'text-white/40 hover:text-primary' }}">
                        <x-heroicon-s-hand-thumb-up class="w-4 h-4" />
                        <span class="text-xs font-bold">{{ $item->like_count ?? 0 }}</span>
                    </button>
                    <div class="w-px h-4 bg-white/10"></div>
                    <button wire:click="toggleLike('{{ $item->id }}', false)"
                        class="px-3 py-1.5 flex items-center gap-2 rounded-full hover:bg-red-500/10 transition-all {{ $isDisliked ? 'text-red-500 bg-red-500/10' : 'text-white/40 hover:text-red-500' }}">
                        <x-heroicon-s-hand-thumb-down class="w-4 h-4" />
                        <span class="text-xs font-bold">{{ $item->dislike_count ?? 0 }}</span>
                    </button>
                </div>
            @else
                <div class="flex items-center gap-2 text-white/40">
                    <x-heroicon-s-hand-thumb-up class="w-4 h-4" />
                    <span class="text-xs">{{ $item->helpful_count ?? 0 }} kişi bu incelemeyi yararlı buldu</span>
                </div>
            @endif
        </div>

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

    {{-- Reply Input --}}
    @if(isset($showReplyInput[$item->id]) && $showReplyInput[$item->id])
        <div class="mt-6 ml-16 animate-in slide-in-from-top-2 fade-in duration-200">
            <div class="relative group/reply-input">
                <textarea wire:model="replyContent.{{ $item->id }}"
                    class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white text-sm placeholder:text-white/30 focus:border-primary/50 focus:ring-0 focus:outline-none focus:bg-white/10 transition-all resize-y min-h-24"
                    placeholder="Yanıtınızı yazın..."></textarea>
                <div class="absolute bottom-3 right-3">
                    <button wire:click="submit('{{ $item->id }}')"
                        class="bg-primary hover:bg-primary-hover text-white p-2 rounded-full shadow-lg hover:shadow-primary/30 hover:scale-105 transition-all">
                        <x-heroicon-s-paper-airplane class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>
    @endif
</li>

{{-- Recursive Replies Loop - Rendered as Siblings --}}
@if($activeTab === 'comments' && $item->replies->count() > 0)
    @foreach($item->replies as $reply)
        @include('livewire.anime.partials.comment-item', ['item' => $reply, 'isReply' => true])
    @endforeach
@endif