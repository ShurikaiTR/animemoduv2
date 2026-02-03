<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function user()
    {
        return Auth::user();
    }

    #[Computed]
    public function profile()
    {
        return $this->user?->profile;
    }

    #[Computed]
    public function displayName()
    {
        return $this->profile?->username 
            ?? $this->profile?->full_name 
            ?? $this->user?->name 
            ?? 'Misafir';
    }

    #[Computed]
    public function avatar()
    {
        return $this->profile?->avatar_url ?: asset('default-avatar.webp');
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('home'), navigate: true);
    }
}; ?>

<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    {{-- Trigger --}}
    <button @click="open = !open"
        class="flex items-center gap-3 cursor-pointer group p-1.5 pr-2.5 rounded-full hover:bg-white/5 transition-all duration-300 border border-transparent hover:border-white/5"
        :class="{ 'bg-white/5 border-white/5': open }">
        <div
            class="w-8 h-8 rounded-full bg-linear-to-br from-primary/20 to-primary/5 flex items-center justify-center border border-primary/30 group-hover:from-primary/30 group-hover:to-primary/10 transition-all shadow-primary-mini overflow-hidden">
            <img src="{{ $this->avatar }}" alt="{{ $this->displayName }}"
                class="w-full h-full object-cover">
        </div>
        <div class="hidden sm:flex flex-col items-start text-left">
            <span class="text-sm font-medium text-white max-w-32 truncate group-hover:text-primary transition-colors">
                {{ $this->displayName }}
            </span>
        </div>
        <x-heroicon-o-chevron-down
            class="w-4 h-4 text-white/50 group-hover:text-primary transition-colors duration-300 ml-1 transition-transform"
            ::class="{ 'rotate-180': open }" />
    </button>

    {{-- Dropdown Menu --}}
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="absolute right-0 mt-2 w-64 bg-bg-secondary border-none text-white p-1.5 shadow-2xl rounded-2xl z-[100]">
        <div class="font-normal p-0 mb-2">
            <div class="flex flex-col space-y-1 bg-white/5 p-3 rounded-xl border border-white/5">
                <p class="text-sm font-medium leading-none text-white">Hesabım</p>
                <p class="text-xs leading-none text-white/40 truncate font-mono mt-1.5">
                    {{ $this->user?->email }}
                </p>
            </div>
        </div>

        <div class="space-y-1">
            <a href="{{ url('/profil') }}" wire:navigate
                class="flex items-center gap-2.5 hover:bg-primary/10 hover:text-primary cursor-pointer rounded-xl p-2.5 transition-colors group">
                <div
                    class="p-1.5 rounded-md bg-white/5 text-white/70 group-hover:bg-primary/20 group-hover:text-primary transition-colors">
                    <x-icons.user class="h-4 w-4" />
                </div>
                <span class="font-medium">Profil</span>
            </a>
            <a href="{{ url('/settings') }}" wire:navigate
                class="flex items-center gap-2.5 hover:bg-primary/10 hover:text-primary cursor-pointer rounded-xl p-2.5 transition-colors group">
                <div
                    class="p-1.5 rounded-md bg-white/5 text-white/70 group-hover:bg-primary/20 group-hover:text-primary transition-colors">
                    <x-heroicon-o-cog-6-tooth class="h-4 w-4" />
                </div>
                <span class="font-medium">Ayarlar</span>
            </a>
        </div>

        <div class="h-px bg-white/10 my-2"></div>

        <button wire:click="logout"
            class="flex items-center gap-2.5 w-full text-danger hover:bg-danger/10 hover:text-danger cursor-pointer rounded-xl p-2.5 transition-colors group">
            <div class="p-1.5 rounded-md bg-danger/10 text-danger group-hover:bg-danger/20 transition-colors">
                <x-heroicon-o-arrow-left-on-rectangle class="h-4 w-4" />
            </div>
            <span class="font-medium text-left">Çıkış Yap</span>
        </button>
    </div>
</div>