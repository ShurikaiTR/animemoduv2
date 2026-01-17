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
    : (!$isReply ? 'bg-bg-secondary/40 hover:bg-bg-secondary/60 backdrop-blur-sm border border-white/5 hover:border-white/10 shadow-lg hover:shadow-2xl hover:border-primary/20' : '') }}">

    @if(!$isReply && $item->is_pinned)
        <div class="flex items-center gap-2 mb-4 text-primary border-b border-primary/10 pb-3">
            <x-heroicon-s-bookmark class="w-4 h-4" />
            <span class="text-xs font-black uppercase tracking-widest">SABİTLENMİŞ YORUM</span>
        </div>
    @endif

    {{-- Author Header & Meta --}}
    @include('livewire.anime.partials.comment-header', ['item' => $item, 'profile' => $profile, 'isAdmin' => $isAdmin, 'activeTab' => $activeTab])

    {{-- Top Right Area (Rating & Pin) --}}
    <div class="absolute top-6 right-6 flex items-center gap-3 z-20">
        @if(!$isReply && auth()->check() && auth()->user()->profile->role === 'admin')
            <button wire:click="pinComment('{{ $item->id }}')"
                class="p-2 rounded-xl bg-black/20 backdrop-blur-md border border-white/5 text-white/40 hover:text-primary hover:border-primary/20 transition-all group-hover/comment:opacity-100 {{ $item->is_pinned ? '!text-primary border-primary/20 bg-primary/10 opacity-100' : 'opacity-0' }}"
                title="{{ $item->is_pinned ? 'Sabitlemeyi Kaldır' : 'Sabitle' }}">
                @if($item->is_pinned)
                    <x-heroicon-s-bookmark class="w-5 h-5" />
                @else
                    <x-heroicon-o-bookmark class="w-5 h-5" />
                @endif
            </button>
        @endif

        @if($activeTab === 'reviews' && $item->rating)
            <div
                class="flex items-center gap-2 bg-slate-800/80 backdrop-blur-md px-4 py-2 rounded-xl border border-slate-700/50 shadow-lg">
                <x-heroicon-s-star class="w-5 h-5 text-primary drop-shadow-glow" />
                <div class="flex items-baseline gap-0.5">
                    <span class="text-xl font-bold text-white leading-none">{{ $item->rating }}</span>
                    <span class="text-xs font-medium text-slate-500 self-end pb-0.5">/10</span>
                </div>
            </div>
        @endif
    </div>


    {{-- Content --}}
    <div
        class="pl-16 relative z-10 {{ $activeTab === 'reviews' ? 'border-l-4 border-primary pl-6 py-2 ml-16 !pl-6' : '' }}">
        @if($activeTab === 'reviews' && $item->title)
            <div class="mb-3">
                <h4 class="font-bold text-xl text-white leading-tight tracking-tight">
                    {{ $item->title }}
                </h4>
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
            <div
                class="{{ $activeTab === 'reviews' ? 'text-white/80 text-lg leading-relaxed' : 'text-white/80 text-[15px] leading-7 font-normal' }}">
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
    @include('livewire.anime.partials.comment-actions', ['item' => $item, 'activeTab' => $activeTab, 'isLiked' => $isLiked, 'isDisliked' => $isDisliked])

    {{-- Reply Input --}}
    @if(isset($showReplyInput[$item->id]) && $showReplyInput[$item->id])
        <div class="mt-6 ml-16 animate-in slide-in-from-top-2 fade-in duration-200">
            <div class="relative group/reply-input">
                <textarea wire:model="replyContent.{{ $item->id }}"
                    class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-white text-sm placeholder:text-white/30 focus:border-primary/50 focus:ring-0 focus:outline-none focus:bg-white/10 transition-all resize-y min-h-24"
                    placeholder="Yanıtınızı yazın..."></textarea>
                <div class="absolute bottom-3 right-3">
                    <button wire:click="submit('{{ $item->id }}')"
                        class="bg-primary hover:bg-primary-hover text-white p-2 rounded-full hover:scale-105 transition-all">
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