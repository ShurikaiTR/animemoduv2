@props(['followers' => 0, 'following' => 0])

<div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 sm:gap-8 py-1">
    <div class="flex items-baseline gap-1.5">
        <span class="text-lg sm:text-xl font-bold text-white leading-none">{{ $following }}</span>
        <span class="text-xs uppercase tracking-widest text-[#e0e0e0]/40 font-bold">Takip</span>
    </div>
    <div class="flex items-baseline gap-1.5">
        <span class="text-lg sm:text-xl font-bold text-white leading-none">{{ $followers }}</span>
        <span class="text-xs uppercase tracking-widest text-[#e0e0e0]/40 font-bold">Takipçi</span>
    </div>
</div>