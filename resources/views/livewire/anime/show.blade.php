<?php

declare(strict_types=1);

use App\Models\Anime;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Locked;
use Livewire\Component;

new #[Lazy]
    #[Layout('components.layout.app')]
    class extends Component {
    #[Locked]
    public string $slug;

    public function placeholder(): View
    {
        return view('livewire.anime.partials.show-skeleton');
    }

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    #[Computed(cache: true, seconds: 86400)]
    public function anime(): Anime
    {
        return Anime::where('slug', $this->slug)->firstOrFail();
    }

    #[Computed]
    public function characters(): array
    {
        return $this->anime->characters ?? [];
    }

    #[Computed]
    public function trailer(): ?string
    {
        return $this->anime->trailer_key;
    }

    #[Computed]
    public function pageTitle(): string
    {
        return $this->anime->title . ' - ' . config('app.name');
    }
}; ?>

<div>
    <x-slot:title>
        {{ $this->pageTitle }}
    </x-slot:title>
    <x-anime.details-hero :anime="$this->anime" :trailer="$this->trailer" />

    <section class="relative overflow-hidden pb-16 pt-8 bg-bg-main">
        <x-layout.container>
            <div class="flex flex-col xl:flex-row xl:items-start gap-12">
                <div class="flex-1 min-w-0 space-y-12">
                    {{-- Episode List (Livewire) --}}
                    <div id="episode-list">
                        <livewire:anime.episode-list :anime="$this->anime" />
                    </div>

                    {{-- Cast List --}}
                    <x-anime.cast-list :characters="$this->characters" />

                    {{-- Comments Section --}}
                    <livewire:anime.comments :anime="$this->anime" lazy />
                </div>
            </div>
        </x-layout.container>
    </section>
</div>