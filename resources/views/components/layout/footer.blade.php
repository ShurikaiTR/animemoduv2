<footer class="bg-bg-main pt-20 pb-12 border-t border-bg-secondary mt-auto">
    <div class="container mx-auto px-4 sm:px-8">
        <div class="flex flex-wrap -mx-4">
            <div class="w-full sm:w-1/2 lg:w-4/12 px-4 mb-8 lg:mb-0">
                <a href="{{ url('/') }}" class="relative block w-36 h-10">
                    <img src="{{ asset(config('site.logo')) }}" alt="{{ config('site.name') }}" loading="lazy"
                        class="object-contain w-full h-full" width="144" height="40" />
                </a>
                <p class="text-sm leading-6 text-text-main mb-5 whitespace-pre-line">
                    {{ config('site.footer_text') }}
                </p>

                {{-- Sosyal Medya --}}
                @php $socials = config('site.socials'); @endphp
                @if(array_filter($socials))
                    <div class="flex items-center gap-4">
                        @if($socials['x'])
                            <a href="{{ $socials['x'] }}" target="_blank" rel="noopener noreferrer"
                                class="text-text-main hover:text-primary transition-colors" aria-label="X (Twitter)">
                                <x-heroicon-o-chat-bubble-left-right class="w-5 h-5" />
                            </a>
                        @endif
                        @if($socials['instagram'])
                            <a href="{{ $socials['instagram'] }}" target="_blank" rel="noopener noreferrer"
                                class="text-text-main hover:text-primary transition-colors" aria-label="Instagram">
                                <x-heroicon-o-camera class="w-5 h-5" />
                            </a>
                        @endif
                        @if($socials['discord'])
                            <a href="{{ $socials['discord'] }}" target="_blank" rel="noopener noreferrer"
                                class="text-text-main hover:text-primary transition-colors" aria-label="Discord">
                                <x-heroicon-m-chat-bubble-bottom-center-text class="w-5 h-5" />
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <div class="w-1/2 sm:w-1/3 lg:w-2/12 px-4 mb-8 lg:mb-0">
                <h6 class="text-white text-base font-medium mb-5">Kaynaklar</h6>
                <ul class="flex flex-col gap-2.5">
                    <li><a href="{{ url('/hakkimizda') }}"
                            class="text-sm text-text-main hover:text-primary transition-colors">Hakkımızda</a></li>
                    <li><a href="{{ url('/takvim') }}"
                            class="text-sm text-text-main hover:text-primary transition-colors">Yayın Takvimi</a></li>
                    <li><a href="{{ url('/yardim') }}"
                            class="text-sm text-text-main hover:text-primary transition-colors">Yardım Merkezi</a></li>
                    <li><a href="{{ url('/iletisim') }}"
                            class="text-sm text-text-main hover:text-primary transition-colors">İletişim</a></li>
                </ul>
            </div>

            <div class="w-1/2 sm:w-1/3 lg:w-2/12 px-4 mb-8 lg:mb-0">
                <h6 class="text-white text-base font-medium mb-5">Yasal</h6>
                <ul class="flex flex-col gap-2.5">
                    <li><a href="{{ url('/kullanim-kosullari') }}"
                            class="text-sm text-text-main hover:text-primary transition-colors">Kullanım Koşulları</a>
                    </li>
                    <li><a href="{{ url('/gizlilik') }}"
                            class="text-sm text-text-main hover:text-primary transition-colors">Gizlilik Politikası</a>
                    </li>
                    <li><a href="{{ url('/cerez-politikasi') }}"
                            class="text-sm text-text-main hover:text-primary transition-colors">Çerezler</a></li>
                </ul>
            </div>

            <div class="w-full sm:w-1/3 lg:w-4/12 px-4">
                <h6 class="text-white text-base font-medium mb-5">İletişim</h6>
                <p class="text-sm leading-6 text-text-main mb-2.5">
                    <a href="tel:{{ config('site.phone') }}"
                        class="hover:text-primary transition-colors">{{ config('site.phone') }}</a>
                </p>
                <p class="text-sm leading-6 text-text-main">
                    <a href="mailto:{{ config('site.email') }}"
                        class="hover:text-primary transition-colors">{{ config('site.email') }}</a>
                </p>
            </div>
        </div>

        <div
            class="border-t border-bg-secondary mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <span class="text-xs text-text-main">© {{ config('site.name') }}, {{ date('Y') }}. Tüm hakları
                saklıdır.</span>
            <div class="flex gap-4">
                <a href="{{ url('/gizlilik') }}"
                    class="text-xs text-text-main hover:text-primary transition-colors">Gizlilik Politikası</a>
                <a href="{{ url('/kullanim-kosullari') }}"
                    class="text-xs text-text-main hover:text-primary transition-colors">Kullanım Koşulları</a>
            </div>
        </div>
    </div>
</footer>