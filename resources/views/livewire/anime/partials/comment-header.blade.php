@props(['item', 'profile', 'isAdmin', 'activeTab'])

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
                        class="flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-primary/10 border border-primary/20 text-2xs font-bold text-primary uppercase tracking-wider">
                        <x-heroicon-s-shield-check class="w-3 h-3" />
                        Yönetici
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