@props([
    'src' => null,
    'poster' => null
])

<div class="w-full h-full bg-black relative group rounded-xl overflow-hidden">
    @if($src)
        {{-- Durum 1: SRC bir HTML Embed koduysa (<iframe... ile başlıyorsa) --}}
        @if(str_contains($src, '<iframe'))
            <div class="w-full h-full [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0">
                {!! $src !!}
            </div>
            
        {{-- Durum 2: SRC bir URL ise ve .mp4/.mkv içermiyorsa (Muhtemelen harici link) --}}
        @elseif(str_contains($src, 'http') && !str_contains($src, '.mp4') && !str_contains($src, '.mkv') && !str_contains($src, '.m3u8'))
            <iframe 
                src="{{ $src }}" 
                class="w-full h-full border-0" 
                allowfullscreen 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            ></iframe>

        {{-- Durum 3: Doğrudan Video Dosyası (MP4/WebM) --}}
        @else
            <video 
                controls 
                class="w-full h-full object-contain"
                poster="{{ $poster }}"
                preload="metadata"
            >
                <source src="{{ $src }}" type="video/mp4">
                Tarayıcınız video etiketini desteklemiyor.
            </video>
        @endif
    @else
        <div class="flex items-center justify-center w-full h-full text-white/50 bg-gray-900">
            <div class="text-center flex flex-col items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
                <p>Video kaynağı bulunamadı.</p>
            </div>
        </div>
    @endif
</div>
