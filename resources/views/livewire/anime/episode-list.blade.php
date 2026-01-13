<div class="mb-12 relative group/list" x-data="{ 
    scroll(direction) {
        const container = $refs.episodeList;
        const amount = direction === 'left' ? -320 : 320;
        container.scrollBy({ left: amount, behavior: 'smooth' });
    }
}">
    <div class="flex items-center justify-between mb-6">
        <h3 class="flex items-center gap-3 text-2xl text-white font-rubik font-normal">
            <x-icons.monitor-play class="w-6 h-6 text-primary" />
            @if($structureType === 'seasonal')
                {{ $selectedSeason }}. Sezon
            @else
                Bölümler
            @endif
        </h3>

        <div class="flex items-center gap-4">
            @if($structureType === 'seasonal' && $seasons->count() > 1)
                <div class="flex gap-2 mr-2">
                    @foreach($seasons as $season)
                        <button wire:click="selectSeason({{ $season }})"
                            class="px-3 py-1 rounded-lg text-sm font-medium transition-colors {{ $selectedSeason === $season ? 'bg-primary text-white' : 'bg-bg-secondary text-text-main hover:bg-white hover:text-bg-secondary' }}">
                            S{{ $season }}
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="hidden md:flex items-center gap-2">
                <x-anime.scroll-button direction="left" @click="scroll('left')" />
                <x-anime.scroll-button direction="right" @click="scroll('right')" />
            </div>
        </div>
    </div>

    <div x-ref="episodeList"
        class="flex gap-4 overflow-x-auto pb-4 -mx-4 px-4 no-scrollbar snap-x snap-mandatory scroll-smooth">
        @forelse($episodes as $episode)
            <div class="min-w-[85%] md:min-w-[20rem] snap-start">
                @php
                    $epNum = ($structureType === 'absolute')
                        ? ($episode->absolute_episode_number ?? $episode->episode_number) . '. Bölüm'
                        : $episode->episode_number . '. Bölüm';
                @endphp
                <x-anime.episode-card :title="$epNum" :episodeNumber="$episode->title"
                    :image="app(\App\Services\TmdbService::class)->getImageUrl($episode->still_path ?? $anime->backdrop_path, 'w500')" :timeAgo="''" :href="route('anime.show', ['slug' => $anime->slug]) . '#episode-' . $episode->episode_number" />
            </div>
        @empty
            <div class="w-full text-center py-8 text-white/40">
                Bu sezon için henüz bölüm bulunmuyor.
            </div>
        @endforelse
    </div>
</div>