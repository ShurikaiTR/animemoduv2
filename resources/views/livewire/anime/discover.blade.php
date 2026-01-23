<div class="min-h-screen pb-16 bg-bg-main">
    <div class="container mx-auto px-4 md:px-6">
        <div class="flex flex-col lg:flex-row gap-0 lg:gap-10 items-start pt-24 lg:pt-32">
            
            {{-- Mobile Filter Bar (Horizontal Scroll) --}}
            <div class="lg:hidden w-full mb-6">
                <div class="flex items-center gap-3 overflow-x-auto pb-4 scrollbar-hide">
                    {{-- Search Input --}}
                    <div class="relative min-w-[200px]">
                        <input 
                            wire:model.live.debounce.300ms="search" 
                            type="text" 
                            placeholder="Anime ara..." 
                            class="w-full pl-9 pr-4 py-2 bg-bg-secondary rounded-xl border border-white/10 text-sm text-white focus:outline-none focus:border-primary/50 transition-colors"
                        >
                        <x-icons.search class="w-4 h-4 text-text-main absolute left-3 top-1/2 -translate-y-1/2" />
                    </div>

                    {{-- Sort Dropdown (Simple Select for Mobile) --}}
                    <select 
                        wire:model.live="sort" 
                        class="px-4 py-2 bg-bg-secondary rounded-xl border border-white/10 text-sm text-white focus:outline-none focus:border-primary/50 transition-colors appearance-none"
                    >
                        <option value="yeni">Yeni Eklenenler</option>
                        <option value="populer">Popüler</option>
                        <option value="puan">Puan</option>
                        <option value="eski">Eskiler</option>
                    </select>
                </div>
                
                {{-- Genre Chips --}}
                <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                    <button 
                        wire:click="toggleGenre('hepsi')"
                        class="px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition-all duration-300 {{ empty($genre) ? 'bg-primary text-white shadow-[0_0_15px_rgba(var(--primary),0.4)]' : 'bg-bg-secondary text-text-main hover:text-white border border-white/5' }}"
                    >
                        Hepsi
                    </button>
                    @foreach($this->availableGenres as $genreEnum)
                        @php
                            $isActive = in_array($genreEnum->value, explode(',', $genre));
                        @endphp
                        <button 
                            wire:click="toggleGenre('{{ $genreEnum->value }}')"
                            class="px-3 py-1.5 rounded-full text-xs font-medium whitespace-nowrap transition-all duration-300 {{ $isActive ? 'bg-primary text-white shadow-[0_0_15px_rgba(var(--primary),0.4)]' : 'bg-bg-secondary text-text-main hover:text-white border border-white/5' }}"
                        >
                            {{ $genreEnum->label() }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Desktop Sidebar --}}
            <aside class="hidden lg:block w-64 shrink-0 sticky top-32 space-y-8">
                {{-- Search --}}
                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-white/50 uppercase tracking-wider font-rubik">Arama</h3>
                    <div class="relative group">
                        <input 
                            wire:model.live.debounce.300ms="search"
                            type="text" 
                            placeholder="Anime ara..." 
                            class="w-full pl-10 pr-4 py-3 bg-bg-secondary rounded-xl border border-white/5 text-sm text-white focus:outline-none focus:border-primary/50 focus:bg-bg-secondary/80 transition-all duration-300 placeholder:text-text-main/50"
                        >
                        <x-icons.search class="w-5 h-5 text-text-main absolute left-3 top-1/2 -translate-y-1/2 group-focus-within:text-primary transition-colors duration-300" />
                    </div>
                </div>

                {{-- Sort --}}
                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-white/50 uppercase tracking-wider font-rubik">Sıralama</h3>
                    <div class="space-y-1">
                        @foreach([
                            'populer' => 'Popüler',
                            'yeni' => 'En Yeni',
                            'puan' => 'IMDb Puanı',
                            'eski' => 'Eskiler',
                        ] as $key => $label)
                            <button 
                                wire:click="$set('sort', '{{ $key }}')"
                                class="w-full text-left px-3 py-2 rounded-lg text-sm transition-all flex items-center justify-between group {{ $sort === $key ? 'bg-primary/10 text-primary font-medium' : 'text-text-main hover:bg-primary/5 hover:text-primary' }}"
                            >
                                {{ $label }}
                                @if($sort === $key)
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Genres --}}
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-white/50 uppercase tracking-wider font-rubik">Türler</h3>
                        @if(!empty($genre))
                            <button 
                                wire:click="toggleGenre('hepsi')"
                                class="text-xs text-primary hover:underline"
                            >
                                Temizle
                            </button>
                        @endif
                    </div>
                    
                    <div class="space-y-1 max-h-[60vh] overflow-y-auto pr-2 custom-scrollbar">
                        <button 
                            wire:click="toggleGenre('hepsi')"
                            class="w-full text-left px-3 py-2 rounded-lg text-sm transition-all flex items-center justify-between group {{ empty($genre) ? 'bg-primary/10 text-primary font-medium' : 'text-text-main hover:bg-primary/5 hover:text-primary' }}"
                        >
                            Hepsi
                            @if(empty($genre))
                                <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                            @endif
                        </button>
                        @foreach($this->availableGenres as $genreEnum)
                            @php
                                $isActive = in_array($genreEnum->value, explode(',', $genre));
                            @endphp
                            <button 
                                wire:click="toggleGenre('{{ $genreEnum->value }}')"
                                class="w-full text-left px-3 py-2 rounded-lg text-sm transition-all flex items-center justify-between group {{ $isActive ? 'bg-primary/10 text-primary font-medium' : 'text-text-main hover:bg-primary/5 hover:text-primary' }}"
                            >
                                {{ $genreEnum->label() }}
                                @if($isActive)
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </aside>

            {{-- Main Content --}}
            <div class="flex-1 w-full min-h-[500px]">
                {{-- Header (Desktop Only) --}}
                <div class="hidden lg:block mb-8">
                    <h1 class="text-3xl font-bold text-white font-rubik tracking-tight mb-2">Keşfet</h1>
                    <p class="text-text-main/60 text-sm">
                        Toplam <span class="text-primary font-bold">{{ $this->animes->total() }}</span> içerik listeleniyor
                        @if($this->animes->currentPage() > 1)
                            • Sayfa {{ $this->animes->currentPage() }}/{{ $this->animes->lastPage() }}
                        @endif
                    </p>
                </div>

                {{-- Active Filters & Skeleton --}}
                <div class="mb-6">
                    {{-- Active Chips --}}
                    @if($genre)
                        <div class="flex flex-wrap gap-2 mb-6 animate-in fade-in slide-in-from-top-2">
                            @foreach(explode(',', $genre) as $g)
                                @if($enum = \App\Enums\AnimeGenre::tryFrom($g))
                                    <button 
                                        wire:click="toggleGenre('{{ $g }}')"
                                        class="flex items-center gap-2 pl-3 pr-2 py-1.5 bg-primary/10 text-primary border border-primary/20 rounded-lg text-sm font-medium hover:bg-primary/20 transition-colors group"
                                    >
                                        {{ $enum->label() }}
                                        <x-icons.close class="w-3.5 h-3.5 opacity-60 group-hover:opacity-100" />
                                    </button>
                                @endif
                            @endforeach
                            @if($this->animes->isNotEmpty())
                                <button 
                                    wire:click="$set('genre', '')" 
                                    class="text-xs text-text-main hover:text-white underline underline-offset-4 decoration-white/20 hover:decoration-white transition-all ml-2"
                                >
                                    Hepsini Temizle
                                </button>
                            @endif
                        </div>
                    @endif

                    {{-- Grid Content --}}
                    <div class="relative min-h-[500px]">
                         {{-- Loading State (Skeleton) --}}
                        <div wire:loading.flex wire:target="sort, genre, search, toggleGenre, updatedSearch" class="flex flex-col w-full absolute inset-0 z-10 bg-bg-main/50 backdrop-blur-[1px]">
                             <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-5">
                                @for($i = 0; $i < 10; $i++)
                                    <div class="w-full aspect-[2/3] rounded-2xl overflow-hidden bg-bg-secondary relative animate-pulse">
                                        {{-- Rating Badge Skeleton --}}
                                        <div class="absolute top-3 right-3 w-10 h-10 rounded-full bg-white/5"></div>
                                        
                                        {{-- Content Skeleton --}}
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
                                    <div class="col-span-full flex flex-col items-center justify-center py-20 text-center animate-in fade-in duration-700">
                                        <div class="w-24 h-24 rounded-full bg-white/5 flex items-center justify-center mb-6">
                                            <svg class="w-10 h-10 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-white mb-2">Sonuç Bulunamadı</h3>
                                        <p class="text-text-main/60 max-w-sm">
                                            Aradığınız kriterlere uygun içerik bulunamadı. Filtreleri temizleyip tekrar deneyin.
                                        </p>
                                        <button 
                                            wire:click="$set('genre', ''); $set('search', '');"
                                            class="mt-6 px-6 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg text-sm font-medium transition-colors"
                                        >
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
                    {{ $this->animes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
