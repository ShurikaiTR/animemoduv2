@props(['age' => null, 'location' => null, 'joinDate' => null])

<div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 sm:gap-3 pt-2">
    @if($age)
        <div
            class="flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 bg-white/[0.03] border border-white/[0.05] rounded-full text-xs sm:text-xs font-semibold text-[#e0e0e0]/40">
            <div class="w-1.5 h-1.5 rounded-full bg-primary shadow-[0_0_8px_rgba(47,128,237,0.4)]"></div>
            {{ $age }} Yaşında
        </div>
    @endif

    @if($location)
        <div
            class="flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 bg-white/[0.03] border border-white/[0.05] rounded-full text-xs sm:text-xs font-semibold text-[#e0e0e0]/40">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-primary/60" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
            </svg>
            {{ $location }}
        </div>
    @endif

    @if($joinDate)
        <div
            class="flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 bg-white/[0.03] border border-white/[0.05] rounded-full text-xs sm:text-xs font-semibold text-[#e0e0e0]/40">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-primary/60" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            {{ $joinDate }} tarihinde katıldı
        </div>
    @endif
</div>