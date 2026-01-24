@props([
    'anime',
    'episodes',
    'currentEpisode'
])

<aside {{ $attributes->merge(['class' => 'w-full']) }} aria-label="Bölüm Navigasyonu">
    <div class="sticky top-24 space-y-6">
        {{-- Anime Card --}}
        <a 
            href="{{ route('anime.show', $anime->slug) }}" 
            aria-label="{{ $anime->title }} anime detayına git"
            class="flex gap-4 p-4 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition-colors group"
        >
            <div class="relative w-16 aspect-[2/3] shrink-0 rounded-lg overflow-hidden bg-black/20">
                 @if($anime->poster_path)
                    <img 
                        src="{{ \App\Services\TmdbService::getImageUrl($anime->poster_path, 'w342') }}" 
                        alt="{{ $anime->title }}" 
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                    >
                @endif
            </div>
            <div class="flex flex-col justify-center min-w-0">
                <span class="text-[10px] text-primary font-bold uppercase tracking-wider mb-1">Şu an İzleniyor</span>
                <h2 class="text-white font-bold font-rubik text-base line-clamp-1 group-hover:text-primary transition-colors">
                    {{ $anime->title }}
                </h2>
                <span class="text-xs text-white/50 mt-1">Anime Detayına Git &rarr;</span>
            </div>
        </a>

        {{-- Episode List --}}
        <div 
            class="bg-bg-secondary/20 rounded-xl border border-white/5 overflow-hidden flex flex-col h-[600px] max-h-[calc(100vh-200px)]"
            x-data="{ activeSeason: {{ $currentEpisode->season_number ?? 1 }} }"
        >
            <div class="p-4 border-b border-white/5 bg-white/[0.02] space-y-4 shrink-0">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-white font-rubik flex items-center gap-2 text-sm">
                        Bölümler
                    </h2>
                    <span class="px-2 py-0.5 rounded-md border border-white/10 bg-white/5 text-xs text-white/60">
                        {{ $episodes->count() }}
                    </span>
                </div>

            </div>
            
            <div class="overflow-y-auto custom-scrollbar p-3 space-y-2 flex-1">
                {{-- Season Tabs --}}
                @php
                    $seasons = $episodes->pluck('season_number')->unique()->sort()->values();
                @endphp

                @if($seasons->count() > 1)
                    <nav class="flex items-center gap-2 mb-4 px-1" aria-label="Sezon seçimi">
                        <span class="text-xs font-semibold text-white/40 uppercase tracking-wider flex items-center gap-1.5 shrink-0">
                            Sezon
                        </span>
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @foreach($seasons as $season)
                                <button
                                    type="button"
                                    @click="activeSeason = {{ $season }}"
                                    title="{{ $season == 0 ? 'Özel Bölümler' : $season . '. Sezon' }}"
                                    :class="activeSeason === {{ $season }} 
                                        ? 'bg-primary text-white shadow-lg shadow-primary/20' 
                                        : 'bg-white/5 text-white/50 hover:bg-white/10 hover:text-white border border-white/5 hover:border-white/10'"
                                    class="w-11 h-11 rounded-lg text-sm font-bold transition-all flex items-center justify-center outline-none focus-visible:ring-2 focus-visible:ring-primary"
                                >
                                    {{ $season == 0 ? 'Ö' : $season }}
                                </button>
                            @endforeach
                        </div>
                    </nav>
                @endif
                
                @foreach($episodes as $ep)
                    @php
                        $isActive = $currentEpisode && $currentEpisode->id === $ep->id;
                        $url = $anime->is_seasonal 
                            ? route('anime.watch', ['anime' => $anime->slug, 'segment1' => "sezon-{$ep->season_number}", 'segment2' => "bolum-{$ep->episode_number}"])
                            : route('anime.watch', ['anime' => $anime->slug, 'segment1' => "bolum-{$ep->episode_number}"]);
                    @endphp

                    <a 
                        href="{{ $url }}"
                        wire:navigate
                        x-show="activeSeason === {{ $ep->season_number }}"
                        class="block p-3 rounded-lg border transition-all duration-200 group relative overflow-hidden
                        {{ $isActive 
                            ? 'bg-primary/20 border-primary/50' 
                            : 'bg-white/5 border-transparent hover:bg-white/10 hover:border-white/10' 
                        }}"
                    >
                        <div class="flex gap-3">
                            <div class="relative w-24 aspect-video shrink-0 rounded bg-black/40 overflow-hidden">
                                @if($ep->still_path)
                                    <img 
                                        src="{{ \App\Services\TmdbService::getImageUrl($ep->still_path, 'w300') }}" 
                                        alt="{{ $ep->season_number }}. Sezon {{ $ep->episode_number }}. Bölüm Önizlemesi" 
                                        class="w-full h-full object-cover {{ $isActive ? '' : 'opacity-70 group-hover:opacity-100 transition-opacity' }}"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center text-white/20">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                    </div>
                                @endif
                                
                                @if($isActive)
                                    <div class="absolute inset-0 bg-primary/20 flex items-center justify-center">
                                        <div class="bg-primary text-white text-[10px] font-bold px-1.5 py-0.5 rounded shadow-lg animate-pulse">
                                            OYNATILIYOR
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex flex-col justify-center min-w-0 flex-1">
                                <span class="text-xs font-medium {{ $isActive ? 'text-primary' : 'text-white/80 group-hover:text-white' }}">
                                    {{ $ep->season_number }}. Sezon {{ $ep->episode_number }}. Bölüm
                                </span>
                                @if($ep->title)
                                    <h3 class="text-[11px] text-white/50 line-clamp-1 mt-0.5">
                                        {{ $ep->title }}
                                    </h3>
                                @endif
                            </div>

                             @if(!$isActive)
                                <div class="flex items-center px-1 ml-auto">
                                    <div class="rounded-full w-8 h-8 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center bg-primary text-white shadow-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</aside>
