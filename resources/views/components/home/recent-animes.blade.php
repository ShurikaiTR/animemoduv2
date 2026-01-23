@props(['animes'])

@if($animes->isNotEmpty())
    @inject('tmdbService', 'App\Services\TmdbService')
    <section class="pb-12 pt-4" aria-labelledby="recent-animes-title">
        <x-layout.container>

                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 id="recent-animes-title" class="text-2xl font-bold text-white font-rubik">Eklenen Animeler</h2>
                        <p class="text-text-main/60 text-sm mt-1">Kütüphanemize yeni katılan seriler</p>
                    </div>
                    <a href="{{ route('anime.hub') }}"
                        class="text-sm font-medium text-primary hover:text-white transition-colors">
                        Tümünü Gör
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                    @foreach($animes as $anime)
                        <x-anime-card :anime="$anime" />
                    @endforeach
                </div>
            </x-layout.container>
    </section>
@endif