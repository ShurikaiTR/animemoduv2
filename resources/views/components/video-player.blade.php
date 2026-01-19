@props([
    'src' => null,
    'poster' => null
])

<div class="w-full h-full bg-black relative group">
    @if($src)
        @if(str_contains($src, 'iframe') || str_contains($src, 'http'))
             {{-- Basic iframe support --}}
            <iframe 
                src="{{ $src }}" 
                class="w-full h-full border-0" 
                allowfullscreen 
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
            ></iframe>
        @else
             {{-- HTML5 Video support --}}
            <video 
                controls 
                class="w-full h-full object-contain"
                poster="{{ $poster }}"
            >
                <source src="{{ $src }}" type="video/mp4">
                Tarayıcınız video etiketini desteklemiyor.
            </video>
        @endif
    @else
        <div class="flex items-center justify-center w-full h-full text-white/50">
            <div class="text-center">
                <p>Video kaynağı bulunamadı.</p>
            </div>
        </div>
    @endif
</div>
