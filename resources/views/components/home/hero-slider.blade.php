@props(['featuredAnimes' => []])

@if($featuredAnimes->isNotEmpty())
    <div x-data="{
                                        activeSlide: 0,
                                        slides: {{ $featuredAnimes->count() }},
                                        progress: 0,
                                        isPaused: false,
                                        hoveringLeft: false,
                                        touchStartX: 0,
                                        touchEndX: 0,
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
                                        },
                                        handleTouchStart(e) {
                                            this.touchStartX = e.changedTouches[0].screenX;
                                        },
                                        handleTouchEnd(e) {
                                            this.touchEndX = e.changedTouches[0].screenX;
                                            this.handleSwipe();
                                        },
                                        handleSwipe() {
                                            if (this.touchEndX < this.touchStartX - 50) {
                                                this.next();
                                            }
                                            if (this.touchEndX > this.touchStartX + 50) {
                                                this.prev();
                                            }
                                        }
                                    }" x-init="setInterval(() => tick(), 50)" @touchstart="handleTouchStart($event)"
        @touchend="handleTouchEnd($event)" role="region" aria-label="Öne Çıkan Animeler" {{ $attributes->merge(['class' => 'relative w-full h-128 md:h-144 lg:h-160 min-h-128 overflow-hidden group bg-black rounded-none md:rounded-3xl shadow-none md:shadow-2xl ring-0 md:ring-1 ring-white/10']) }}>
        {{-- Slides --}}
        <div class="relative w-full h-full">
            @inject('tmdbService', 'App\Services\TmdbService')
            @foreach($featuredAnimes as $index => $anime)
                @php
                    $backdropUrl = $anime->backdrop_path ? $tmdbService->getImageUrl($anime->backdrop_path, 'original') : asset('img/placeholder-backdrop.jpg');
                    $logoUrl = $anime->logo_path ? $tmdbService->getImageUrl($anime->logo_path, 'original') : null;
                @endphp
                <x-home.hero-slide :anime="$anime" :index="$index" :activeSlide="'activeSlide'" :tmdbService="$tmdbService">
                    @foreach($featuredAnimes as $indicatorIndex => $indicatorAnime)
                        <button @click="goTo({{ $indicatorIndex }})"
                            class="relative h-2.5 rounded-full transition-all duration-300 overflow-hidden bg-white/20 cursor-pointer"
                            :class="activeSlide === {{ $indicatorIndex }} ? 'w-10' : 'w-5'"
                            aria-label="{{ $indicatorIndex + 1 }}. slayta git">
                            <template x-if="activeSlide === {{ $indicatorIndex }}">
                                <div class="absolute top-0 left-0 h-full bg-primary transition-all duration-75"
                                    :style="{ width: (hoveringLeft ? 100 : progress) + '%' }"></div>
                            </template>
                        </button>
                    @endforeach
                </x-home.hero-slide>

            @endforeach
        </div>

        <button @click="prev()"
            class="absolute left-0 top-0 bottom-0 z-30 w-12 md:w-20 hidden md:flex items-center justify-center bg-gradient-to-r from-black/60 to-transparent transition-all duration-300 text-white group cursor-pointer"
            aria-label="Previous Slide">
            <x-icons.chevron-left
                class="w-8 h-8 md:w-10 md:h-10 transform transition-transform group-hover:scale-110 group-active:scale-95" />
        </button>
        <button @click="next()"
            class="absolute right-0 top-0 bottom-0 z-30 w-12 md:w-20 hidden md:flex items-center justify-center bg-gradient-to-l from-black/60 to-transparent transition-all duration-300 text-white group cursor-pointer"
            aria-label="Next Slide">
            <x-icons.chevron-right
                class="w-8 h-8 md:w-10 md:h-10 transform transition-transform group-hover:scale-110 group-active:scale-95" />
        </button>
    </div>
@endif