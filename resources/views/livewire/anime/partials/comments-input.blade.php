@props(['activeTab', 'title', 'rating', 'content', 'isSpoiler', 'message'])

<div class="relative group" wire:key="input-section-{{ $activeTab }}">
    @auth
        <div wire:key="auth-input-{{ $activeTab }}"
            class="bg-bg-secondary/40 backdrop-blur-md rounded-3xl p-1 border border-white/5 shadow-2xl relative overflow-hidden group/form transition-all duration-300 hover:border-white/10 hover:shadow-primary/5">

            {{-- Decorative Gradient --}}
            <div
                class="absolute top-0 right-0 w-96 h-96 bg-primary/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none opacity-50 group-hover/form:opacity-100 transition-opacity duration-1000">
            </div>

            <div class="bg-bg-main/50 rounded-[20px] p-6 relative z-10">
                <div class="flex gap-6">
                    {{-- User Avatar --}}
                    <div class="hidden sm:block shrink-0">
                        <img src="{{ auth()->user()->profile->avatar_url }}" alt="{{ auth()->user()->profile->username }}"
                            class="w-14 h-14 rounded-2xl object-cover shadow-lg ring-1 ring-white/10">
                    </div>

                    <div class="flex-1 flex flex-col gap-6">

                        @if($activeTab === 'reviews')
                            {{-- Review Title & Rating --}}
                            <div class="relative mb-6">
                                {{-- Top Row: Title & Rating --}}
                                <div class="flex flex-col-reverse sm:flex-row items-start justify-between gap-6">
                                    {{-- Left: Title Input --}}
                                    <div class="w-full sm:max-w-xl space-y-2">
                                        <input type="text" wire:model="title" maxlength="100" placeholder="İnceleme Başlığı..."
                                            class="w-full bg-transparent border-none p-0 text-3xl font-black text-white placeholder:text-white/20 focus:ring-0 focus:outline-none tracking-tight">
                                        <div class="h-1 w-20 bg-primary/30 rounded-full"></div>
                                    </div>

                                    {{-- Right: Big Star Rating --}}
                                    <div class="flex items-center gap-4 bg-black/20 p-3 rounded-2xl border border-white/5 backdrop-blur-sm"
                                        x-data="{ hoverRating: 0 }">
                                        <div class="flex items-center gap-1">
                                            @foreach(range(1, 10) as $star)
                                                <button type="button" @mouseenter="hoverRating = {{ $star }}"
                                                    @mouseleave="hoverRating = 0" wire:click="$set('rating', {{ $star }})"
                                                    class="group/star relative">
                                                    <x-heroicon-s-star class="w-6 h-6 transition-all duration-200"
                                                        x-bind:class="(hoverRating ? {{ $star }} <= hoverRating : {{ $star }} <= $wire.rating) ? 'text-primary drop-shadow-glow scale-110' : 'text-white/10 group-hover/star:text-primary/50'" />
                                                </button>
                                            @endforeach
                                        </div>

                                        {{-- Big Score Display --}}
                                        <div class="flex items-center gap-1 pl-4 border-l border-white/10"
                                            x-show="$wire.rating > 0" x-transition>
                                            <x-heroicon-s-star class="w-8 h-8 text-primary" />
                                            <span class="text-3xl font-black text-white tabular-nums leading-none"
                                                x-text="$wire.rating"></span>
                                            <span class="text-xs font-bold text-white/40 self-end mb-1">/10</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Character Limit --}}
                                <div
                                    class="absolute -bottom-4 left-0 text-[10px] font-bold text-white/20 uppercase tracking-widest">
                                    {{ strlen($title) }}/100 KARAKTER
                                </div>
                            </div>
                        @endif

                        {{-- Textarea Container --}}
                        <div class="relative group/input">
                            <textarea wire:model="content"
                                class="w-full bg-transparent !border-0 !ring-0 !outline-none text-white placeholder:text-white/20 p-2 min-h-36 text-lg leading-relaxed resize-none"
                                placeholder="{{ $activeTab === 'reviews' ? 'Bu yapım hakkında neler düşünüyorsunuz? Detaylı bir inceleme yazın...' : 'Düşüncelerini paylaş... Tartışmaya katıl!' }}"></textarea>
                        </div>

                        {{-- Footer Actions --}}
                        <div
                            class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4 border-t border-white/5">

                            {{-- Left: Spoiler & Emoji --}}
                            <div class="flex items-center gap-4">
                                <button type="button"
                                    class="text-white/30 hover:text-white p-2 rounded-lg hover:bg-white/5 transition-colors"
                                    title="Emoji Ekle (Yakında)">
                                    <x-heroicon-o-face-smile class="w-6 h-6" />
                                </button>

                                <label class="flex items-center gap-3 cursor-pointer group/toggle select-none">
                                    <div class="relative">
                                        <input type="checkbox" wire:model="isSpoiler" class="sr-only peer">
                                        <div
                                            class="w-10 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary transition-colors">
                                        </div>
                                    </div>
                                    <span
                                        class="text-sm font-bold text-white/40 group-hover/toggle:text-white/60 transition-colors uppercase tracking-wider">Spoiler?</span>
                                </label>
                            </div>

                            {{-- Right: Submit Button --}}
                            <div class="w-full sm:w-auto">
                                <x-ui.button wire:click="submit" wire:loading.attr="disabled" variant="primary" size="lg"
                                    class="w-full sm:w-auto gap-2 font-bold px-8 shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:scale-105 active:scale-95 transition-all">
                                    <span
                                        wire:loading.remove>{{ $activeTab === 'reviews' ? 'İNCELEMEYİ YAYINLA' : 'GÖNDER' }}</span>
                                    <div wire:loading flex items-center gap-1>
                                        <span class="animate-pulse">YÜKLENİYOR...</span>
                                    </div>
                                    <x-heroicon-s-paper-airplane wire:loading.remove
                                        class="w-4 h-4 -rotate-45 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform" />
                                </x-ui.button>
                            </div>
                        </div>
                    </div>
                </div>

                @error('content')
                    <div class="absolute bottom-4 left-6 animate-in slide-in-from-bottom-2 fade-in">
                        <span
                            class="text-xs text-red-400 font-bold bg-red-400/10 px-3 py-1 rounded-full border border-red-400/20">{{ $message }}</span>
                    </div>
                @enderror
            </div>
        </div>
    @else
        <div
            class="bg-bg-secondary/20 backdrop-blur-sm rounded-3xl p-12 border border-white/5 text-center flex flex-col items-center gap-6 group-hover:border-primary/20 transition-all relative overflow-hidden">
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none">
            </div>

            <div
                class="w-20 h-20 rounded-2xl bg-gradient-to-br from-white/10 to-transparent flex items-center justify-center shadow-2xl shadow-black/50 ring-1 ring-white/5 transform rotate-3 group-hover:rotate-6 transition-transform duration-500">
                <x-heroicon-o-lock-closed class="w-8 h-8 text-white/40" />
            </div>
            <div class="z-10 relative">
                <h4 class="text-white font-black text-2xl mb-2 tracking-tight">Tartışmaya Katıl</h4>
                <p class="text-white/40 text-base max-w-sm mx-auto leading-relaxed">
                    {{ $activeTab === 'reviews' ? 'Bu başyapıt hakkındaki görüşlerini paylaşmak ve puan vermek için giriş yap.' : 'Topluluğun bir parçası ol ve düşüncelerini diğer hayranlarla paylaş.' }}
                </p>
            </div>
            <button @click="$dispatch('openAuthModal')"
                class="mt-2 bg-white text-black hover:bg-white/90 font-black py-4 px-10 rounded-xl transition-all shadow-glow-white hover:shadow-glow-white-lg hover:scale-105 active:scale-95 z-10">
                GİRİŞ YAP
            </button>
        </div>
    @endauth
</div>