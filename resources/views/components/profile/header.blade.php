@props(['user'])

@php
    $avatarSrc = $user->profile?->avatar_url ?? 'https://ui-avatars.com/api/?name=' . $user->name . '&background=random';
    $bannerSrc = $user->profile?->banner_url ?? '/banner-placeholder.webp'; 
@endphp

<div class="container mx-auto px-4 sm:px-8 mb-8">
    <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden bg-bg-secondary border border-white/5 shadow-2xl">
        {{-- Banner --}}
        <div class="relative h-32 sm:h-48 md:h-64 lg:h-72 w-full">
            @if($user->profile?->banner_url)
                <img src="{{ $bannerSrc }}" class="w-full h-full object-cover" alt="Kapak Fotoğrafı">
            @else
                <div class="w-full h-full bg-gradient-to-t from-bg-main to-bg-secondary flex items-center justify-center">
                    <x-icons.image class="w-12 h-12 text-white/5" />
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
        </div>

        {{-- Profile Info --}}
        <div class="relative px-4 sm:px-6 md:px-10 pb-6 sm:pb-8 pt-16 sm:pt-16 md:pt-4 bg-bg-secondary/50">
            {{-- Avatar --}}
            <div
                class="absolute -top-12 left-1/2 -translate-x-1/2 sm:translate-x-0 sm:left-6 md:left-10 sm:-top-16 md:-top-20 z-10">
                <div
                    class="relative w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40 rounded-full sm:rounded-2xl md:rounded-3xl overflow-hidden border-4 border-primary bg-bg-secondary shadow-2xl ring-4 ring-black/20">
                    <img src="{{ $avatarSrc }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @if($user->isAdmin())
                        <div
                            class="absolute bottom-1 right-1 sm:bottom-2 sm:right-2 w-3 h-3 sm:w-4 sm:h-4 bg-green-500 border-2 border-bg-secondary rounded-full shadow-lg z-20">
                        </div>
                    @endif
                </div>
            </div>

            {{-- Content --}}
            <div class="flex flex-col gap-6">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 sm:gap-6">
                    {{-- Left Side: Info --}}
                    <div
                        class="flex flex-col items-center sm:items-start text-center sm:text-left space-y-4 sm:pl-36 md:pl-44 lg:pl-48 w-full">
                        {{-- Name & Badge --}}
                        <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-3">
                            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white tracking-tight">
                                {{ $user->profile?->full_name ?? $user->name }}
                            </h1>
                            <span class="text-[#e0e0e0]/40 text-sm sm:text-lg font-medium">
                                {{ '@' . ($user->profile?->username ?? $user->name) }}
                            </span>
                            @if($user->isAdmin())
                                <span
                                    class="px-2 sm:px-3 py-0.5 sm:py-1 font-medium rounded-full text-xs flex items-center gap-1 sm:gap-1.5 backdrop-blur-md bg-blue-500/10 border border-blue-500/20 text-blue-400">
                                    <x-icons.shield-check class="w-3 h-3 sm:w-3.5 sm:h-3.5" />
                                    Yönetici
                                </span>
                            @endif
                        </div>

                        <x-profile.stats :followers="$user->profile?->followers ?? '0'"
                            :following="$user->profile?->following ?? '0'" />

                        @if($user->profile?->bio)
                            <p class="text-[#e0e0e0]/70 text-sm sm:text-base leading-relaxed max-w-2xl px-2 sm:px-0">
                                {{ $user->profile->bio }}
                            </p>
                        @endif

                        <x-profile.info-pills :age="$user->profile?->age" :location="$user->profile?->location"
                            :join-date="$user->created_at->format('d M Y')" />
                    </div>

                    {{-- Right Side: Actions (Desktop) --}}
                    <div class="hidden sm:flex flex-col items-end gap-3 self-start shrink-0">
                        <x-profile.actions :user="$user" :is-own-profile="auth()->id() === $user->id" />
                        <x-profile.socials :socials="$user->profile?->social_media ?? []" />
                    </div>
                </div>

                {{-- Mobile Actions Stack --}}
                <div class="flex sm:hidden flex-col items-center gap-4 pt-2 border-t border-white/5 w-full mt-6">
                    <x-profile.actions :user="$user" :is-own-profile="auth()->id() === $user->id" class="w-full" />
                    <div class="scale-110 pt-2">
                        <x-profile.socials :socials="$user->profile?->social_media ?? []" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>