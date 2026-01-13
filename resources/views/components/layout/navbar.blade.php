<div x-data="{ isScrolled: false, mobileMenuOpen: false }" x-init="window.addEventListener('scroll', () => { isScrolled = window.scrollY > 20 })">
    {{-- Main Header --}}
    <header
        :class="{
            'bg-bg-main/95 backdrop-blur-sm border-bg-secondary': isScrolled || mobileMenuOpen,
            'bg-transparent border-transparent': !isScrolled && !mobileMenuOpen,
            'top-64': mobileMenuOpen,
            'top-0': !mobileMenuOpen
        }"
        class="fixed left-0 w-full z-[100] transition-all duration-500 border-b border-transparent"
    >
        <div class="container mx-auto px-4 sm:px-8">
            <div class="relative xl:overflow-visible">
                <div class="flex flex-row justify-between items-center h-20 lg:h-24 relative">
                    {{-- Mobile Menu Button --}}
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="relative block w-6 h-6 group z-40 xl:hidden"
                        :class="{ 'active': mobileMenuOpen }"
                        type="button"
                        :aria-label="mobileMenuOpen ? 'Menüyü kapat' : 'Menüyü aç'"
                    >
                        <span
                            class="absolute left-0 h-0.5 rounded-sm transition-all duration-500 transform origin-center"
                            :class="{ 'bg-primary rotate-45 top-2.5 w-6': mobileMenuOpen, 'bg-white top-0 w-6': !mobileMenuOpen }"
                        ></span>
                        <span
                            class="absolute left-0 h-0.5 rounded-sm transition-all duration-500 transform origin-center"
                            :class="{ 'opacity-0 bg-primary top-2.5 w-4': mobileMenuOpen, 'opacity-100 bg-white top-2.5 w-4': !mobileMenuOpen }"
                        ></span>
                        <span
                            class="absolute left-0 h-0.5 rounded-sm transition-all duration-500 transform origin-center"
                            :class="{ 'bg-primary -rotate-45 top-2.5 w-6': mobileMenuOpen, 'bg-white top-5 w-2': !mobileMenuOpen }"
                        ></span>
                    </button>

                    {{-- Logo --}}
                    <a href="{{ url('/') }}" class="relative block w-32 md:w-48 h-10 md:h-12 ml-4 mr-auto xl:ml-0 xl:mr-0">
                        <img
                            src="{{ asset(config('site.logo')) }}"
                            alt="{{ config('site.name') }}"
                            class="object-contain w-full h-full"
                            width="192"
                            height="48"
                        />
                    </a>

                    {{-- Desktop Navigation --}}
                    <nav class="hidden xl:block">
                        <ul class="flex flex-row items-center">
                            <li class="relative xl:mr-16">
                                <a href="{{ url('/animeler') }}" class="text-sm font-medium text-text-main hover:text-primary transition-colors">Animeler</a>
                            </li>
                            <li class="relative xl:mr-16">
                                <a href="{{ url('/filmler') }}" class="text-sm font-medium text-text-main hover:text-primary transition-colors">Filmler</a>
                            </li>
                            <li class="relative xl:mr-16">
                                <a href="{{ url('/kesfet') }}" class="text-sm font-medium text-text-main hover:text-primary transition-colors">Keşfet</a>
                            </li>
                            <li class="relative" x-data="{ open: false }" @click.outside="open = false">
                                <button 
                                    @click="open = !open"
                                    class="text-sm font-medium text-text-main hover:text-primary transition-colors outline-none cursor-pointer leading-5"
                                >
                                    <x-icons.more class="w-6 h-6 inline-block align-middle" />
                                </button>
                                <div
                                    x-show="open"
                                    x-cloak
                                    x-transition:enter="transition ease-out duration-300 transform"
                                    x-transition:enter-start="opacity-0 translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    class="absolute top-full left-0 mt-4 w-48 bg-bg-secondary border-none text-white p-1.5 shadow-2xl rounded-2xl z-[60]"
                                >
                                    <a href="{{ url('/takvim') }}" class="flex items-center px-4 py-2.5 text-sm font-medium hover:text-primary transition-colors rounded-xl">Takvim</a>
                                    <a href="{{ url('/sss') }}" class="flex items-center px-4 py-2.5 text-sm font-medium hover:text-primary transition-colors rounded-xl">SSS</a>
                                    <a href="{{ url('/iletisim') }}" class="flex items-center px-4 py-2.5 text-sm font-medium hover:text-primary transition-colors rounded-xl">İletişim</a>
                                    @if(config('site.social.discord'))
                                        <a href="{{ config('site.social.discord') }}" target="_blank" rel="noopener noreferrer" class="flex items-center px-4 py-2.5 text-sm font-medium hover:text-discord transition-colors rounded-xl">Discord</a>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    </nav>

                    {{-- Right Actions --}}
                    <div class="flex items-center justify-end gap-4 lg:gap-6">
                        <livewire:layout.navbar-search />

                        @auth
                            <x-ui.button variant="link" size="icon" class="group/bell !p-0">
                                <x-heroicon-o-bell class="w-6 h-6 group-hover/bell:text-primary transition-colors" />
                            </x-ui.button>
                            <livewire:layout.user-menu />
                        @else
                            <x-ui.button 
                                variant="link"
                                @click="$dispatch('openAuthModal')"
                                class="flex items-center gap-2.5 group/login cursor-pointer !px-0"
                                aria-label="Giriş yap"
                            >
                                <span class="text-sm font-medium text-text-main group-hover/login:text-primary transition-colors">
                                    Giriş Yap
                                </span>
                                <span class="text-primary group-hover/login:text-primary transition-colors" aria-hidden="true">
                                    <x-icons.login class="w-5 h-5" />
                                </span>
                            </x-ui.button>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Mobile Navigation (Slides down from top) --}}
    <ul
        :class="{ 'top-0 opacity-100 visible': mobileMenuOpen, '-top-64 opacity-0 invisible': !mobileMenuOpen }"
        class="fixed left-0 w-full bg-bg-main border-b border-bg-secondary z-[50] transition-all duration-500 flex flex-col justify-start items-start p-5 overflow-y-auto h-64 xl:hidden"
    >
        <li class="relative mb-5 w-full">
            <a href="{{ url('/animeler') }}" class="text-sm font-medium text-text-main hover:text-primary transition-colors block">Animeler</a>
        </li>
        <li class="relative mb-5 w-full">
            <a href="{{ url('/filmler') }}" class="text-sm font-medium text-text-main hover:text-primary transition-colors block">Filmler</a>
        </li>
        <li class="relative mb-5 w-full">
            <a href="{{ url('/kesfet') }}" class="text-sm font-medium text-text-main hover:text-primary transition-colors block">Keşfet</a>
        </li>
        <li class="relative mb-5 w-full" x-data="{ open: false }">
            <button @click="open = !open" class="text-sm font-medium text-text-main hover:text-primary transition-colors flex items-center gap-2">
                Daha Fazla <x-heroicon-o-chevron-down class="w-4 h-4 transition-transform" ::class="{ 'rotate-180': open }" />
            </button>
            <div x-show="open" x-cloak class="mt-4 pl-4 space-y-4 border-l border-white/5">
                <a href="{{ url('/takvim') }}" class="text-sm font-medium text-text-main/60 hover:text-primary block">Takvim</a>
                <a href="{{ url('/sss') }}" class="text-sm font-medium text-text-main/60 hover:text-primary block">SSS</a>
                <a href="{{ url('/iletisim') }}" class="text-sm font-medium text-text-main/60 hover:text-primary block">İletişim</a>
            </div>
        </li>
    </ul>

    <livewire:auth.auth-modal />
</div>