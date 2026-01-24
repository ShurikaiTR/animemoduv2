<div>
    <x-slot:title>
        {{ $this->getPageTitle() }}
    </x-slot:title>
    <x-anime.details-hero :anime="$anime" :trailer="$this->trailer" />

    <section class="relative overflow-hidden pb-16 pt-8 bg-bg-main">
        <x-layout.container>
            <div class="flex flex-col xl:flex-row xl:items-start gap-12">
                <div class="flex-1 min-w-0 space-y-12">
                    {{-- Episode List (Livewire) --}}
                    <div id="episode-list">
                        <livewire:anime.episode-list :anime="$anime" />
                    </div>

                    {{-- Cast List --}}
                    <x-anime.cast-list :characters="$this->characters" />

                    {{-- Comments Section --}}
                    <livewire:anime.comments :anime="$anime" lazy />
                </div>
            </div>
        </x-layout.container>
    </section>
</div>