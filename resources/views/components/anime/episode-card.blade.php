@props(['title', 'episodeNumber', 'image', 'imageW300' => null, 'href' => '#', 'timeAgo' => '', 'attr' => ''])

<div class="group relative w-full aspect-video rounded-2xl overflow-hidden cursor-pointer bg-bg-secondary">
    <a href="{{ $href }}" class="block w-full h-full relative">
        {{-- Poster Image --}}
        @if($image)
            <img src="{{ $image }}" @if($imageW300) srcset="{{ $imageW300 }} 300w, {{ $image }} 500w"
            sizes="(max-width: 640px) 85vw, 320px" @endif alt="{{ $title }} - {{ $episodeNumber }} anime bölüm görseli"
                loading="lazy" {{ $attr }}
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105" />
        @endif

        {{-- Gradient Overlay --}}
        <div
            class="absolute inset-x-0 bottom-0 h-full bg-gradient-to-t from-black via-black/80 to-transparent z-10 transition-opacity duration-500 group-hover:opacity-90">
        </div>

        {{-- Hover Inset Shadow --}}
        <div
            class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none shadow-inset-dark">
        </div>

        {{-- Play Button Wrapper --}}
        <div
            class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 z-10">
            {{-- Outer Circle (Blurry) --}}
            <div
                class="w-12 h-12 rounded-full bg-primary/20 backdrop-blur-sm border border-primary/50 flex items-center justify-center shadow-glow-lg scale-50 group-hover:scale-100 transition-transform duration-500">
                {{-- Inner Circle --}}
                <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center shadow-lg text-white">
                    <x-icons.play class="w-4 h-4 ml-0.5 fill-current" />
                </div>
            </div>
        </div>

        {{-- Time/Badge --}}
        @if($timeAgo)
            <div class="absolute top-2 right-2 z-10">
                <span
                    class="px-2 py-0.5 bg-black/40 backdrop-blur-md rounded-lg text-2xs font-medium text-white/90 border border-white/10 flex items-center gap-1 shadow-lg">
                    {{ $timeAgo }}
                </span>
            </div>
        @endif

        {{-- Content --}}
        <div
            class="absolute bottom-0 left-0 right-0 p-4 translate-y-2 group-hover:translate-y-0 transition-transform duration-500 ease-out z-20">
            <h3
                class="text-white font-black font-rubik text-[0.95rem] leading-tight line-clamp-1 group-hover:text-primary transition-colors drop-shadow-md">
                {{ $title }}
            </h3>

            @php
                $cleanedTitle = trim(strtolower((string) $title));
                $cleanedNumber = trim(strtolower((string) $episodeNumber));
                $showNumber = $episodeNumber && $cleanedNumber !== $cleanedTitle && $cleanedNumber !== '';
            @endphp

            @if($showNumber)
                <div class="flex items-center gap-2 text-2xs text-gray-400 font-normal mt-0.5">
                    <span>{{ $episodeNumber }}</span>
                </div>
            @endif
        </div>

        {{-- Hover Border --}}
        <div
            class="absolute inset-0 border-2 border-primary/0 group-hover:border-primary/50 rounded-2xl transition-colors duration-500 pointer-events-none">
        </div>
    </a>
</div>