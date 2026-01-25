<div class="min-h-screen pb-20 bg-bg-main">
    <div class="container mx-auto px-4 md:px-6 pt-24 md:pt-32 pb-10 font-rubik">

        {{-- Header Section (At the very top) --}}
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-extrabold text-white font-rubik tracking-tight mb-2 drop-shadow-md">
                Yayın Takvimi
            </h1>
            <p class="text-text-main/60 text-sm">
                Haftalık anime yayın akışını takip et, yeni bölümleri kaçırma.
            </p>
        </div>

        {{-- Day Selector (Sticky, below header) --}}
        <div class="sticky top-20 z-40 backdrop-blur-xl border-y border-white/5 py-4 mb-10 -mx-4 md:mx-0 px-4 md:px-0">
            <div
                class="flex items-center gap-2 md:grid md:grid-cols-7 overflow-x-auto md:overflow-visible no-scrollbar">
                @foreach($days as $day)
                    <button wire:click="setDay('{{ $day->value }}')" @class([
                        'flex flex-col items-center justify-center py-3 px-6 md:px-0 rounded-xl transition-all duration-300 min-w-24 md:min-w-0 md:w-full relative overflow-hidden group',
                        'bg-primary text-white shadow-lg shadow-primary/20 scale-105' => $activeDay === $day->value,
                        'bg-white/5 text-white/50 hover:bg-white/10 hover:text-white' => $activeDay !== $day->value
                    ])>
                        <span class="text-xs font-bold uppercase tracking-wider opacity-70 mb-1">
                            {{ mb_substr($day->getLabel(), 0, 3) }}
                        </span>
                        <span @class(['text-lg font-bold', 'scale-110' => $activeDay === $day->value])>
                            {{ $day->getLabel() }}
                        </span>

                        @if($activeDay === $day->value)
                            <div class="absolute bottom-0 left-0 w-full h-1 bg-white/20"></div>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Anime List (Vertical Stack) --}}
        <div class="grid grid-cols-1 gap-6 animate-in fade-in slide-in-from-bottom-8 duration-500"
            wire:loading.class="opacity-50 grayscale transition-all duration-500">
            @forelse($animes as $anime)
                <x-anime.calendar-card :anime="$anime" :wire:key="'anime-'.$anime->id" />
            @empty
                <div class="py-20 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-6">
                        <x-icons.star class="w-8 h-8 text-white/20" />
                    </div>
                    <h2 class="text-xl font-bold text-white mb-2">Bölüm Yok</h2>
                    <p class="text-white/40">Bu gün için henüz planlanmış bir anime bölümü bulunmuyor.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12 text-center text-white/40 text-sm">
            <p>Veriler otomatik olarak güncellenmektedir.</p>
        </div>
    </div>
</div>