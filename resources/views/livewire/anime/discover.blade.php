<div class="min-h-screen pb-16 bg-bg-main">
    <x-slot:title>
        {{ $this->getPageTitle() }}
    </x-slot:title>
    <div class="container mx-auto px-4 md:px-6">
        <div class="flex flex-col lg:flex-row gap-0 lg:gap-10 items-start pt-24 lg:pt-32">

            {{-- Mobile Filter Bar --}}
            <x-anime.discover-mobile-filters :sort="$sort" :genres="$genres" :search="$search"
                :available-genres="$this->availableGenres" class="lg:hidden" />

            {{-- Desktop Sidebar --}}
            <x-anime.discover-sidebar :sort="$sort" :genres="$genres" :search="$search"
                :available-genres="$this->availableGenres" class="hidden lg:block" />

            {{-- Main Content --}}
            <div class="flex-1 w-full min-h-[500px]">
                {{-- Header (Desktop Only) --}}
                <div class="hidden lg:block mb-8">
                    <h1
                        class="text-3xl md:text-4xl font-extrabold text-white font-rubik tracking-tight mb-2 drop-shadow-md">
                        {{ $this->pageHeading }}
                    </h1>
                    <p class="text-text-main/60 text-sm">
                        Toplam <span class="text-primary font-bold">{{ $this->animes->total() }}</span> içerik
                        listeleniyor
                        @if($this->animes->currentPage() > 1)
                            • Sayfa {{ $this->animes->currentPage() }}/{{ $this->animes->lastPage() }}
                        @endif
                    </p>
                </div>

                {{-- Active Filters & Content --}}
                <div class="mb-6">
                    {{-- Active Chips --}}
                    @if(!empty($genres))
                        <div class="flex flex-wrap gap-2 mb-6 animate-in fade-in slide-in-from-top-2">
                            @foreach($genres as $g)
                                @if($enum = \App\Enums\AnimeGenre::tryFrom($g))
                                    <button wire:click="toggleGenre('{{ $g }}')"
                                        class="flex items-center gap-2 pl-3 pr-2 py-1.5 bg-primary/10 text-primary border border-primary/20 rounded-lg text-sm font-medium hover:bg-primary/20 transition-colors group">
                                        {{ $enum->label() }}
                                        <x-icons.close class="w-3.5 h-3.5 opacity-60 group-hover:opacity-100" />
                                    </button>
                                @endif
                            @endforeach
                            @if($this->animes->isNotEmpty())
                                <button wire:click="resetFilters"
                                    class="text-xs text-text-main hover:text-white underline underline-offset-4 decoration-white/20 hover:decoration-white transition-all ml-2">
                                    Hepsini Temizle
                                </button>
                            @endif
                        </div>
                    @endif

                    {{-- Grid Content --}}
                    <div class="relative min-h-[500px]">
                        {{-- Loading State (Skeleton) --}}
                        <div wire:loading.flex wire:target="sort, genre, search, toggleGenre, updatedSearch"
                            class="flex flex-col w-full absolute inset-0 z-10 bg-bg-main/50 backdrop-blur-[1px]">
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">
                                @for($i = 0; $i < 10; $i++)
                                    <div
                                        class="w-full aspect-[2/3] rounded-2xl overflow-hidden bg-bg-secondary relative animate-pulse">
                                        <div class="absolute top-3 right-3 w-10 h-10 rounded-full bg-white/5"></div>
                                        <div class="absolute bottom-0 left-0 right-0 p-5 space-y-3">
                                            <div class="h-5 bg-white/10 rounded w-3/4"></div>
                                            <div class="flex gap-2">
                                                <div class="h-3 bg-white/10 rounded w-12"></div>
                                                <div class="h-3 bg-white/10 rounded w-8"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        {{-- Actual Content --}}
                        <div wire:loading.remove wire:target="sort, genre, search, toggleGenre, updatedSearch">
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">
                                @forelse($this->animes as $anime)
                                    <x-anime-card :anime="$anime" wire:key="anime-{{ $anime->id }}" />
                                @empty
                                    <div
                                        class="col-span-full flex flex-col items-center justify-center py-20 text-center animate-in fade-in duration-700">
                                        <div
                                            class="w-24 h-24 rounded-full bg-white/5 flex items-center justify-center mb-6">
                                            <svg class="w-10 h-10 text-white/20" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-white mb-2">Sonuç Bulunamadı</h3>
                                        <p class="text-text-main/60 max-w-sm">
                                            Aradığınız kriterlere uygun içerik bulunamadı. Filtreleri temizleyip tekrar
                                            deneyin.
                                        </p>
                                        <button wire:click="resetFilters"
                                            class="mt-6 px-6 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg text-sm font-medium transition-colors">
                                            Filtreleri Temizle
                                        </button>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    @if($this->animes->hasMorePages())
                        <div x-intersect.full="$wire.loadMore()">
                            <div wire:loading wire:target="loadMore" wire:loading.delay
                                class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5 w-full mt-4">
                                @foreach(range(1, 10) as $i)
                                    <div class="aspect-[2/3] w-full rounded-2xl overflow-hidden">
                                        <div class="w-full h-full bg-white/5 animate-pulse"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>