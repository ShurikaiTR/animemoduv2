<div class="flex items-center" x-data="{ isOpen: @entangle('isOpen') }">
    {{-- Mobil Arama Butonu --}}
    <button type="button" @click="isOpen = !isOpen"
        class="xl:hidden flex items-center justify-center text-primary hover:text-white transition-colors duration-300"
        aria-label="Aramayı aç">
        <x-icons.search class="w-5 h-5" />
    </button>

    {{-- Arama Formu --}}
    <form wire:submit.prevent=""
        :class="{ 'opacity-100 translate-y-0 pointer-events-auto': isOpen, 'opacity-0 -translate-y-4 pointer-events-none xl:opacity-100 xl:translate-y-0 xl:pointer-events-auto': !isOpen }"
        class="xl:relative xl:w-64 xl:top-auto xl:left-auto xl:right-auto xl:bottom-auto absolute left-0 right-0 top-0 h-20 xl:h-auto bg-bg-main xl:bg-transparent flex flex-row items-center justify-start px-4 xl:px-0 transition-all duration-500 z-50 overflow-visible">
        <div class="relative w-full">
            <input wire:model.live.debounce.300ms="query" type="text" placeholder="Anime ara..." autoComplete="off"
                class="h-10 rounded-2xl bg-bg-secondary border-none pl-5 pr-12 text-sm text-white placeholder:text-text-main/70 focus:outline-none focus:ring-0 transition-all duration-300 w-[calc(100%-2.5rem)] xl:w-full"
                @focus="$wire.showResults = true" />
            <button type="submit"
                class="absolute top-1/2 -translate-y-1/2 flex items-center justify-center w-5 h-5 text-primary hover:text-white transition-colors duration-300 z-10 right-14 xl:right-4"
                aria-label="Ara">
                <x-icons.search class="w-5 h-5" />
            </button>
            <button type="button" @click="isOpen = false"
                class="absolute right-0 xl:hidden top-1/2 -translate-y-1/2 flex items-center justify-center w-10 h-full text-primary/70 hover:text-primary transition-opacity duration-300 z-10"
                aria-label="Aramayı kapat">
                <x-icons.close class="w-5 h-5" />
            </button>

            {{-- Arama Sonuçları --}}
            @if($showResults && strlen($query) >= 2)
                <div
                    class="absolute top-full left-0 right-0 xl:right-auto mt-2 w-full xl:w-96 bg-bg-secondary/95 backdrop-blur-md rounded-xl border border-white/10 shadow-2xl overflow-hidden z-[100]">
                    <div class="p-4 text-center text-white/40 text-sm">
                        "{{ $query }}" için sonuçlar yakında burada...
                    </div>
                </div>
            @endif
        </div>
    </form>
</div>