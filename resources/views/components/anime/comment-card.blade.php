@props(['item', 'activeTab' => 'comments', 'isReply' => false, 'revealedSpoilers' => []])

@php
    $profile = $item->user->profile;
    $isAdmin = $profile->role === 'admin';
    $hasRevealed = isset($revealedSpoilers[$item->id]);
    $avatarSize = $isReply ? 'w-9 h-9 text-xs' : 'w-12 h-12 text-lg';
    $ringClass = $isAdmin ? 'ring-2 ring-primary/60 shadow-glow animate-pulse' : 'ring-1 ring-white/10';
@endphp

<div {{ $attributes->merge(['class' => 'group/card transition-all duration-300 ' . ($isReply ? 'flex gap-4' : 'p-6 rounded-2xl bg-gradient-to-br from-white/[0.04] to-white/[0.01] border border-white/[0.06] hover:border-primary/20')]) }}>
    @if(!$isReply && $item->is_pinned)
        <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-primary/10 border border-primary/20 mb-4 w-fit">
            <x-heroicon-s-hashtag class="w-3.5 h-3.5 text-primary" />
            <span class="text-[10px] font-bold text-primary uppercase tracking-wider">Sabitlenmiş</span>
        </div>
    @endif
    <div class="flex gap-5 w-full">
        {{-- Avatar --}}
        <div class="shrink-0 {{ !$isReply ? 'pt-1' : '' }}">
            <div
                class="relative {{ $avatarSize }} rounded-full overflow-hidden bg-bg-secondary ring-offset-2 ring-offset-bg-main {{ $ringClass }}">
                @if($profile->avatar_url)
                    <img src="{{ $profile->avatar_url }}" class="w-full h-full object-cover">
                @else
                    <div
                        class="w-full h-full flex items-center justify-center text-white font-bold bg-primary/20 uppercase">
                        {{ substr($profile->username, 0, 1) }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-4 mb-3">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <span
                            class="font-bold {{ $isReply ? 'text-sm' : 'text-base' }} hover:underline cursor-pointer {{ $isAdmin ? 'text-primary' : 'text-white' }}">
                            {{ $profile->username }}
                        </span>
                        @if($isAdmin)
                            <span
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary/10 border border-primary/20 {{ $isReply ? 'text-[8px]' : 'text-[10px]' }} font-bold text-primary uppercase tracking-tight">
                                <x-heroicon-s-shield-check class="{{ $isReply ? 'w-2.5 h-2.5' : 'w-3.5 h-3.5' }}" />
                                Yönetici
                            </span>
                        @endif
                        <span class="text-xs text-white/20 font-medium whitespace-nowrap">•</span>
                        <span class="text-xs text-white/40 font-medium uppercase tracking-wider whitespace-nowrap">
                            {{ $item->created_at->diffForHumans() }}
                        </span>
                    </div>
                    @if($activeTab === 'reviews' && $item->title)
                        <h4
                            class="text-lg font-bold text-white line-clamp-1 group-hover/card:text-primary transition-colors">
                            {{ $item->title }}
                        </h4>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    @if($activeTab === 'reviews' && !$isReply)
                        <div
                            class="flex items-center gap-2 px-3 py-2 rounded-xl bg-gradient-to-r from-primary/10 to-transparent border border-primary/20">
                            <x-heroicon-s-star class="w-5 h-5 text-primary" />
                            <span class="text-xl font-black text-white tabular-nums">{{ $item->rating }}</span>
                            <span class="text-xs text-white/40">/10</span>
                        </div>
                    @endif

                    {{-- Admin Actions --}}
                    @if(!$isReply)
                        @auth
                            @if(auth()->user()->profile->role === 'admin')
                                <button wire:click="pinComment('{{ $item->id }}')"
                                    class="p-2 rounded-lg bg-white/5 hover:bg-primary/20 border border-white/10 hover:border-primary/30 transition-all {{ $item->is_pinned ? 'text-primary' : 'text-white/40' }} hover:text-primary group/pin"
                                    title="{{ $item->is_pinned ? 'Sabitlemeyi Kaldır' : 'Sabitle' }}">
                                    <x-heroicon-s-bookmark class="w-4 h-4 {{ $item->is_pinned ? 'fill-current' : '' }}" />
                                </button>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>

            <div class="text-white/70 text-sm leading-relaxed mb-4">
                @if($item->is_spoiler && !$hasRevealed)
                    <div
                        class="relative {{ $isReply ? 'min-h-[60px]' : 'min-h-[100px]' }} rounded-xl bg-bg-main/60 backdrop-blur-sm border border-primary/10 overflow-hidden group/spoiler">
                        <div class="p-5 blur-xl select-none opacity-30 transition-all duration-300">
                            {{ $item->content }}
                        </div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 z-10 p-4">
                            <div
                                class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-primary/10 border border-primary/20 backdrop-blur-sm">
                                <div class="w-2 h-2 rounded-full bg-primary animate-pulse shadow-glow-sm"></div>
                                <span class="text-[10px] font-bold text-primary uppercase tracking-widest">Spoiler
                                    İçerik</span>
                            </div>
                            <button wire:click="revealSpoiler('{{ $item->id }}')"
                                class="px-5 py-2 rounded-lg bg-white/5 hover:bg-primary/20 border border-white/10 hover:border-primary/30 text-[11px] font-bold text-white/60 hover:text-white transition-all uppercase">
                                GÖSTER
                            </button>
                        </div>
                    </div>
                @else
                    <p class="{{ $activeTab === 'reviews' && !$isReply ? 'pl-3 border-l-2 border-primary/20' : '' }}">
                        {!! nl2br(e($item->content)) !!}
                    </p>
                @endif
            </div>

            {{-- Slot for Actions and Replies --}}
            {{ $slot }}
        </div>
    </div>
</div>