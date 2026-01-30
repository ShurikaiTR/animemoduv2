@props(['user', 'isOwnProfile' => false, 'isFollowing' => false, 'isPending' => false])

@if ($isOwnProfile)
    <button onclick="alert('Düzenleme modali henüz aktif değil')"
        class="h-10 sm:h-11 px-4 sm:px-8 rounded-xl shadow-lg shadow-primary/20 text-sm sm:text-base w-full sm:w-auto bg-primary text-white hover:bg-primary-hover transition-colors flex items-center justify-center font-medium">
        <x-icons.edit class="w-4 h-4 mr-2" />
        <span class="hidden sm:inline">Profili düzenle</span>
        <span class="sm:hidden">Düzenle</span>
    </button>
@else
    <button wire:click="toggleFollow" @disabled($isPending)
        class="h-10 sm:h-11 px-4 sm:px-8 rounded-xl text-sm sm:text-base w-full sm:w-auto flex items-center justify-center font-medium transition-colors {{ $isFollowing ? 'bg-white/5 hover:bg-white/10 text-white border border-white/5' : 'bg-primary text-white hover:bg-primary-hover shadow-lg shadow-primary/20' }}">
        @if($isPending)
            <x-icons.loader-2 class="w-4 h-4 mr-2 animate-spin" />
        @elseif($isFollowing)
            <x-icons.user-check class="w-4 h-4 mr-2" />
        @else
            <x-icons.user-plus class="w-4 h-4 mr-2" />
        @endif

        <span class="hidden sm:inline">
            {{ $isFollowing ? "Takip Ediliyor" : "Takip Et" }}
        </span>
        <span class="sm:hidden">
            {{ $isFollowing ? "Takipte" : "Takip" }}
        </span>
    </button>
@endif