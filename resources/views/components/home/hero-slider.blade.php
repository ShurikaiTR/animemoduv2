@props(['featuredAnimes' => []])

@if($featuredAnimes->isNotEmpty())
<div
    x-data="{
        activeSlide: 0,
        slides: {{ $featuredAnimes->count() }},
        progress: 0,
        isPaused: false,
        hoveringLeft: false,
        next() {
            this.progress = 0;
            this.activeSlide = (this.activeSlide + 1) % this.slides;
        },
        prev() {
            this.progress = 0;
            this.activeSlide = (this.activeSlide - 1 + this.slides) % this.slides;
        },
        goTo(index) {
            this.progress = 0;
            this.activeSlide = index;
        },
        tick() {
            if (!this.isPaused) {
                this.progress += 0.5;
                if (this.progress >= 100) {
                    this.next();
                }
            }
        }
    }"
    x-init="setInterval(() => tick(), 50)"
    {{ $attributes->merge(['class' => 'relative w-full h-96 md:h-128 lg:h-160 min-h-96 overflow-hidden group bg-black rounded-3xl shadow-2xl ring-1 ring-white/10']) }}
>
    {{-- Slides --}}
    <div class="relative w-full h-full">
        @inject('tmdbService', 'App\Services\TmdbService')
        @foreach($featuredAnimes as $index => $anime)
            @php
                $backdropUrl = $anime->backdrop_path ? $tmdbService->getImageUrl($anime->backdrop_path, 'original') : asset('img/placeholder-backdrop.jpg');
                $logoUrl = $anime->logo_path ? $tmdbService->getImageUrl($anime->logo_path, 'original') : null;
            @endphp
            <x-home.hero-slide 
                :anime="$anime" 
                :index="$index" 
                :activeSlide="'activeSlide'"
                :tmdbService="$tmdbService"
            >
                @foreach($featuredAnimes as $indicatorIndex => $indicatorAnime)
                    <button
                        @click="goTo({{ $indicatorIndex }})"
                        class="relative h-2.5 rounded-full transition-all duration-300 overflow-hidden bg-white/20 cursor-pointer"
                        :class="activeSlide === {{ $indicatorIndex }} ? 'w-10' : 'w-5'"
                    >
                        <template x-if="activeSlide === {{ $indicatorIndex }}">
                            <div 
                                class="absolute top-0 left-0 h-full bg-primary transition-all duration-75"
                                :style="{ width: (hoveringLeft ? 100 : progress) + '%' }"
                            ></div>
                        </template>
                    </button>
                @endforeach
            </x-home.hero-slide>

        @endforeach
    </div>

    <button
        @click="prev()"
        class="absolute left-0 top-0 bottom-0 z-30 w-12 md:w-20 flex items-center justify-center bg-gradient-to-r from-black/60 to-transparent transition-all duration-300 text-white group cursor-pointer"
        aria-label="Previous Slide"
    >
        <x-icons.chevron-left class="w-8 h-8 md:w-10 md:h-10 transform transition-transform group-hover:scale-110 group-active:scale-95" />
    </button>
    <button
        @click="next()"
        class="absolute right-0 top-0 bottom-0 z-30 w-12 md:w-20 flex items-center justify-center bg-gradient-to-l from-black/60 to-transparent transition-all duration-300 text-white group cursor-pointer"
        aria-label="Next Slide"
    >
        <x-icons.chevron-right class="w-8 h-8 md:w-10 md:h-10 transform transition-transform group-hover:scale-110 group-active:scale-95" />
    </button>
    @endif
</div>
