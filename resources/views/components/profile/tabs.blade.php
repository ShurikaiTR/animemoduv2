@props(['activeTab', 'tabs' => []])

<div class="w-full mb-8">
    {{-- Desktop Tabs --}}
    <div
        class="hidden md:flex items-center gap-1 p-1 bg-bg-secondary/50 backdrop-blur-sm border border-white/5 rounded-xl w-fit">
        @foreach($tabs as $tab)
            <button wire:click="setTab('{{ $tab['id'] }}')"
                class="relative px-6 py-2.5 rounded-lg text-sm font-medium transition-all duration-300 whitespace-nowrap z-10 {{ $activeTab === $tab['id'] ? 'text-white' : 'text-white/50 hover:text-white hover:bg-white/5' }}">
                @if($activeTab === $tab['id'])
                    <div class="absolute inset-0 bg-primary rounded-lg -z-10 shadow-lg shadow-primary/25"></div>
                @endif
                {{ $tab['label'] }}
            </button>
        @endforeach
    </div>

    {{-- Mobile Dropdown --}}
    <div class="md:hidden" x-data="{ open: false }">
        <div class="relative">
            <button @click="open = !open" @click.away="open = false"
                class="flex items-center justify-between w-full px-5 py-3.5 bg-bg-secondary/50 backdrop-blur-md border border-white/10 rounded-xl text-white font-medium focus:outline-none focus:border-primary/50 transition-all active:scale-[0.98]">
                <span class="flex flex-col items-start leading-tight">
                    <span class="text-xs text-white/40 uppercase tracking-wider font-bold mb-0.5">Kategori</span>
                    <span class="text-sm">
                        @foreach($tabs as $tab)
                            @if($activeTab === $tab['id'])
                                {{ $tab['label'] }}
                            @endif
                        @endforeach
                    </span>
                </span>
                <x-icons.chevron-down class="w-5 h-5 text-white/50" />
            </button>

            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                class="absolute top-full left-0 w-full mt-2 z-50 bg-bg-secondary/95 backdrop-blur-xl border border-white/10 p-2 rounded-2xl shadow-2xl origin-top"
                style="display: none;">
                @foreach($tabs as $tab)
                    <button wire:click="setTab('{{ $tab['id'] }}'); open = false"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 mb-1 last:mb-0 cursor-pointer text-left {{ $activeTab === $tab['id'] ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-1.5 h-1.5 rounded-full transition-all duration-300 {{ $activeTab === $tab['id'] ? 'bg-white scale-125' : 'bg-white/10' }}">
                        </div>
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>