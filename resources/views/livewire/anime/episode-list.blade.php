<?php

declare(strict_types=1);

use App\Models\Anime;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Services\TmdbService;

new class extends Component {
    #[Locked]
    public Anime $anime;

    #[Url(as: 'sezon', history: true)]
    public int $selectedSeason = 1;

    public function mount(Anime $anime): void
    {
        $this->anime = $anime;

        // Find the first available season if not provided in URL
        if (request()->query('sezon') === null) {
            $firstSeason = $this->anime->episodes()
                ->released()
                ->where('season_number', '>', 0)
                ->min('season_number');

            if ($firstSeason) {
                $this->selectedSeason = (int) $firstSeason;
            }
        }
    }

    public function selectSeason(int $season): void
    {
        $this->selectedSeason = $season;
        $this->dispatch('season-changed');
    }

    #[Computed(cache: true, seconds: 3600)]
    public function seasons()
    {
        return $this->anime->episodes()
            ->released()
            ->where('season_number', '>', 0)
            ->select('season_number')
            ->distinct()
            ->orderBy('season_number')
            ->pluck('season_number');
    }

    #[Computed(cache: true, seconds: 3600)]
    public function episodes()
    {
        return $this->anime->episodes()
            ->released()
            ->where('season_number', $this->selectedSeason)
            ->orderBy('episode_number')
            ->get();
    }

    #[Computed]
    public function structureType(): string
    {
        return $this->anime->structure_type ?? 'seasonal';
    }
}; ?>

<div class="mb-12 relative group/list" x-data="{ 
    scroll(direction) {
        const container = $refs.episodeList;
        const amount = direction === 'left' ? -320 : 320;
        container.scrollBy({ left: amount, behavior: 'smooth' });
    }
}" x-on:season-changed.window="$refs.episodeList.scrollTo({ left: 0, behavior: 'smooth' })">
    <div class="flex items-center justify-between mb-6">
        <h3 class="flex items-center gap-3 text-2xl text-white font-rubik font-bold">
            @if($this->structureType === 'seasonal')
                {{ $this->selectedSeason }}. Sezon
            @else
                Bölümler
            @endif
        </h3>

        <div class="flex items-center gap-4">
            @if($this->structureType === 'seasonal' && $this->seasons->count() > 1)
                <div class="flex gap-2 mr-2">
                    @foreach($this->seasons as $season)
                        <button type="button" wire:click="selectSeason({{ $season }})"
                            aria-pressed="{{ $this->selectedSeason === $season ? 'true' : 'false' }}"
                            aria-label="Sezon {{ $season }}"
                            class="px-3 py-1 rounded-lg text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-bg-main {{ $this->selectedSeason === $season ? 'bg-primary text-white' : 'bg-bg-secondary text-text-main hover:bg-white hover:text-bg-secondary' }}">
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
        class="flex gap-4 overflow-x-auto pb-4 -mx-4 px-4 scrollbar-hide snap-x snap-mandatory scroll-smooth">
        @forelse($this->episodes as $episode)
            <div class="min-w-[85%] md:min-w-[20rem] snap-start">
                @php
                    $epNum = ($this->structureType === 'absolute')
                        ? ($episode->absolute_episode_number ?? $episode->episode_number) . '. Bölüm'
                        : $episode->episode_number . '. Bölüm';
                    $href = ($this->structureType === 'seasonal')
                        ? route('anime.watch', ['anime' => $this->anime->slug, 'segment1' => "sezon-{$episode->season_number}", 'segment2' => "bolum-{$episode->episode_number}"])
                        : route('anime.watch', ['anime' => $this->anime->slug, 'segment1' => "bolum-" . ($episode->absolute_episode_number ?? $episode->episode_number)]);
                @endphp
                <x-anime.episode-card :title="$epNum" :episodeNumber="$episode->title"
                    :image="TmdbService::getImageUrl($episode->still_path ?? $this->anime->backdrop_path, 'w500')"
                    :timeAgo="''" :href="$href" />
            </div>
        @empty
            <x-ui.empty-state icon="heroicon-o-video-camera-slash" title="Bölüm Bulunamadı"
                description="Bu sezon için henüz bölüm bulunmuyor." class="w-full py-10" />
        @endforelse
    </div>
</div>