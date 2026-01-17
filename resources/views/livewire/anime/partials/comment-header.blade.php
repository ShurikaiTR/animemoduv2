@props(['item', 'profile', 'isAdmin', 'activeTab'])

<div class="flex items-start justify-between gap-4 mb-4 relative z-10">
    <div class="flex items-center gap-4">
        {{-- Avatar --}}
        <div class="relative group/avatar">
            <div
                class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary/60 to-primary/20 p-0.5 shadow-lg ring-1 ring-white/5 group-hover/avatar:ring-primary/50 transition-all">
                <div class="w-full h-full rounded-[14px] bg-bg-secondary overflow-hidden">
                    <img src="{{ $profile->avatar_url }}" alt="{{ $profile->username }}"
                        class="w-full h-full object-cover">
                </div>
            </div>
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
                        class="bg-primary/10 text-primary text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-primary/20 flex items-center gap-1">
                        <x-heroicon-s-shield-check class="w-3.5 h-3.5" />
                        YÖNETİCİ
                    </span>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs text-white/40">
            <span>{{ $item->created_at->diffForHumans() }}</span>
            @if($activeTab !== 'reviews')
                <span class="w-1 h-1 rounded-full bg-white/20"></span>
            @endif
        </div>
    </div>
</div>