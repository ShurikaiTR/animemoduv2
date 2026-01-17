@props(['position' => 'bottom'])

@php
    $positionClasses = match ($position) {
        'bottom' => 'top-full mt-4',
        'top' => 'bottom-full mb-4',
        default => 'top-full mt-4',
    };
@endphp

<div class="relative" x-data="{ 
    open: false, 
    position: '{{ $position }}',
    toggle() {
        if (!this.open) {
            const rect = this.$el.getBoundingClientRect();
            const spaceBelow = window.innerHeight - rect.bottom;
            const spaceAbove = rect.top;
            
            // If space below is less than 320px and there's more space above, flip to top
            if (spaceBelow < 320 && spaceAbove > spaceBelow) {
                this.position = 'top';
            } else {
                this.position = 'bottom';
            }
        }
        this.open = !this.open;
    }
}" @click.outside="open = false">
    <button type="button" @click="toggle()" {{ $attributes->merge(['class' => 'flex items-center justify-center cursor-pointer focus:outline-none']) }}>
        {{ $trigger ?? '' }}
    </button>

    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" :class="{
            'top-full mt-4': position === 'bottom',
            'bottom-full mb-4': position === 'top',
            'translate-y-0': open,
            'translate-y-2': !open && position === 'bottom',
            '-translate-y-2': !open && position === 'top'
        }"
        class="absolute left-0 w-56 bg-bg-secondary border-none text-white p-1.5 shadow-2xl rounded-2xl z-dropdown transition-transform duration-300">

        <div class="text-xs font-normal text-white/50 mb-2 px-2 uppercase tracking-wider text-left pt-2">
            Listeye Ekle
        </div>

        <div class="space-y-1">
            {{-- İzliyorum --}}
            <button type="button"
                class="flex items-center gap-3 w-full cursor-pointer rounded-xl p-2.5 transition-colors hover:bg-white/5 group text-left">
                <div class="p-1.5 rounded-md bg-white/5 group-hover:bg-primary/20 transition-colors">
                    <x-heroicon-o-eye class="w-4 h-4 text-white/70 group-hover:text-primary transition-colors" />
                </div>
                <span class="font-medium text-white/70 group-hover:text-white transition-colors">İzliyorum</span>
            </button>

            {{-- İzledim --}}
            <button type="button"
                class="flex items-center gap-3 w-full cursor-pointer rounded-xl p-2.5 transition-colors hover:bg-white/5 group text-left">
                <div class="p-1.5 rounded-md bg-white/5 group-hover:bg-success/20 transition-colors">
                    <x-heroicon-o-check class="w-4 h-4 text-white/70 group-hover:text-success transition-colors" />
                </div>
                <span class="font-medium text-white/70 group-hover:text-white transition-colors">İzledim</span>
            </button>

            {{-- İzleyeceğim --}}
            <button type="button"
                class="flex items-center gap-3 w-full cursor-pointer rounded-xl p-2.5 transition-colors hover:bg-white/5 group text-left">
                <div class="p-1.5 rounded-md bg-white/5 group-hover:bg-orange/20 transition-colors">
                    <x-heroicon-o-clock class="w-4 h-4 text-white/70 group-hover:text-orange transition-colors" />
                </div>
                <span class="font-medium text-white/70 group-hover:text-white transition-colors">İzleyeceğim</span>
            </button>

            {{-- Beklemede --}}
            <button type="button"
                class="flex items-center gap-3 w-full cursor-pointer rounded-xl p-2.5 transition-colors hover:bg-white/5 group text-left">
                <div class="p-1.5 rounded-md bg-white/5 group-hover:bg-warning/20 transition-colors">
                    <x-heroicon-o-pause class="w-4 h-4 text-white/70 group-hover:text-warning transition-colors" />
                </div>
                <span class="font-medium text-white/70 group-hover:text-white transition-colors">Beklemede</span>
            </button>

            {{-- Bıraktım --}}
            <button type="button"
                class="flex items-center gap-3 w-full cursor-pointer rounded-xl p-2.5 transition-colors hover:bg-white/5 group text-left">
                <div class="p-1.5 rounded-md bg-white/5 group-hover:bg-danger/20 transition-colors">
                    <x-heroicon-o-x-circle class="w-4 h-4 text-white/70 group-hover:text-danger transition-colors" />
                </div>
                <span class="font-medium text-white/70 group-hover:text-white transition-colors">Bıraktım</span>
            </button>
        </div>
    </div>
</div>