<div class="min-h-screen pt-24 md:pt-32 pb-20 bg-bg-main text-white">
    {{-- Header --}}
    <div class="animate-fade-in-down">
        <x-profile.header :user="$user" />
    </div>

    <div class="container mx-auto px-4 mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Left Side: Library & Tabs --}}
            <div class="lg:col-span-8 xl:col-span-9">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white font-rubik flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                        Kütüphane
                    </h2>
                </div>

                {{-- Tabs --}}
                @php
                    $tabs = [
                        ['id' => 'all', 'label' => 'Tümü'],
                        ['id' => 'watching', 'label' => 'İzliyor'],
                        ['id' => 'completed', 'label' => 'Bitirdi'],
                        ['id' => 'plan_to_watch', 'label' => 'Plan'],
                        ['id' => 'favorites', 'label' => 'Favoriler']
                    ];
                @endphp
                
                <x-profile.tabs :tabs="$tabs" :active-tab="$activeTab" />

                {{-- Content Grid --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    @php
                        $items = $activeTab === 'favorites' 
                            ? $favorites 
                            : ($activeTab === 'all' 
                                ? $watchlist 
                                : $watchlist->filter(fn($i) => $i->status->value === $activeTab));
                    @endphp

                    @forelse($items as $item)
                        <div class="w-full animate-fade-in-up">
                            @if($activeTab === 'favorites')
                                 <x-anime-card :anime="$item->favoritable" />
                            @else
                                <div class="relative group">
                                    <x-anime-card :anime="$item->anime" />
                                    
                                    {{-- Status Badge Overlay --}}
                                    <div class="absolute top-3 left-3 z-30 px-2 py-1 rounded-lg backdrop-blur-md border border-white/10 shadow-lg bg-{{ $item->status->color() }}/90 text-{{ $item->status->color() }}">
                                        <span class="text-xs font-bold font-rubik">{{ $item->status->label() }}</span>
                                    </div>

                                    {{-- Progress Bar Overlay (Bottom) --}}
                                    @if($item->anime->episodes_count > 0)
                                        <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-800/50 z-30">
                                            <div class="h-full bg-primary transition-all duration-300" style="width: {{ min(100, ($item->progress / $item->anime->episodes_count) * 100) }}%"></div>
                                        </div>
                                        <div class="absolute bottom-2 right-2 z-30 px-1.5 py-0.5 rounded bg-black/80 text-[10px] text-white font-bold">
                                            {{ $item->progress }}/{{ $item->anime->episodes_count }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-16 text-center bg-base-800/30 rounded-3xl border border-white/5">
                            <div class="w-16 h-16 bg-base-800 rounded-full flex items-center justify-center mb-4 text-gray-500">
                                @if($activeTab === 'favorites')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Henüz içerik yok</h3>
                            <p class="text-gray-400">Bu listede henüz bir anime bulunmuyor.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Right Side: Activities --}}
            <div class="lg:col-span-4 xl:col-span-3">
                 <x-profile.activities :activities="$activities" :username="$user->name" />
            </div>
        </div>
    </div>
</div>
