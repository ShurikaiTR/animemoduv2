@props(['socials' => []])

@php
    $hasSocials = !empty($socials) && (
        !empty($socials['x']) ||
        !empty($socials['instagram']) ||
        !empty($socials['discord']) ||
        !empty($socials['reddit']) ||
        !empty($socials['telegram'])
    );
@endphp

@if($hasSocials)
    <div class="flex items-center gap-1 bg-white/[0.03] border border-white/[0.05] p-1 rounded-xl">
        {{-- X (Twitter) --}}
        @if(!empty($socials['x']))
            <a href="https://x.com/{{ $socials['x'] }}" target="_blank" rel="noopener noreferrer"
                class="w-10 h-10 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg hover:bg-white/5 transition-colors text-white/40 hover:text-white">
                <x-icons.x class="w-4 h-4" />
            </a>
        @endif

        {{-- Instagram --}}
        @if(!empty($socials['instagram']))
            <a href="https://instagram.com/{{ $socials['instagram'] }}" target="_blank" rel="noopener noreferrer"
                class="w-10 h-10 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg hover:bg-white/5 transition-colors text-white/40 hover:text-[#E1306C]">
                <x-icons.instagram class="w-4 h-4" />
            </a>
        @endif

        {{-- Discord --}}
        @if(!empty($socials['discord']))
            <a href="{{ $socials['discord'] }}" target="_blank" rel="noopener noreferrer"
                class="w-10 h-10 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg hover:bg-white/5 transition-colors text-white/40 hover:text-discord">
                <x-icons.discord class="w-4 h-4" />
            </a>
        @endif

        {{-- Reddit --}}
        @if(!empty($socials['reddit']))
            <a href="https://reddit.com/user/{{ $socials['reddit'] }}" target="_blank" rel="noopener noreferrer"
                class="w-10 h-10 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg hover:bg-white/5 transition-colors text-white/40 hover:text-[#FF4500]">
                <x-icons.reddit class="w-4 h-4" />
            </a>
        @endif

        {{-- Telegram --}}
        @if(!empty($socials['telegram']))
            <a href="https://t.me/{{ $socials['telegram'] }}" target="_blank" rel="noopener noreferrer"
                class="w-10 h-10 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg hover:bg-white/5 transition-colors text-white/40 hover:text-[#0088CC]">
                <x-icons.telegram class="w-4 h-4" />
            </a>
        @endif
    </div>
@endif