@props(['trailerKey', 'title'])

<div x-show="showTrailer" x-cloak x-init="$watch('showTrailer', value => {
        if (value) {
            document.body.classList.add('overflow-hidden');
        } else {
            document.body.classList.remove('overflow-hidden');
        }
    })" class="fixed inset-0 z-top flex items-center justify-center p-4 sm:p-20">

    {{-- Overlay --}}
    <div x-show="showTrailer" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/80 backdrop-blur-xl" @click="showTrailer = false">
    </div>

    {{-- Modal Content --}}
    <div x-show="showTrailer" x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-5xl aspect-video bg-black rounded-3xl overflow-visible shadow-2xl border border-white/10 z-10">

        {{-- Close Button --}}
        <button type="button" @click="showTrailer = false"
            class="absolute -top-12 right-0 z-top text-primary hover:brightness-110 transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-black rounded-full"
            aria-label="Fragmanı kapat">
            <x-icons.close class="w-8 h-8" />
        </button>

        {{-- YouTube iframe (lazy loaded) --}}
        <template x-if="showTrailer">
            <iframe class="w-full h-full rounded-3xl" src="https://www.youtube.com/embed/{{ $trailerKey }}?autoplay=1"
                title="{{ $title }} Fragmanı" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </template>
    </div>
</div>