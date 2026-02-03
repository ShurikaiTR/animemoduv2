<?php

declare(strict_types=1);

use App\Livewire\Auth\Concerns\HasAuthModalConfig;
use App\Livewire\Forms\LoginForm;
use App\Livewire\Forms\RegisterForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {
    use HasAuthModalConfig;

    public LoginForm $loginForm;

    public RegisterForm $registerForm;

    public string $view = 'login'; // 'login', 'register', 'forgot-password'

    public bool $isOpen = false;

    // We keep this for Forgot Password view as a simple property
    public string $forgotPasswordEmail = '';

    #[On('openAuthModal')]
    public function open(string $view = 'login'): void
    {
        $this->resetFields();
        $this->view = $view;
        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->isOpen = false;
        $this->resetFields();
    }

    public function setView(string $view): void
    {
        $this->resetValidation();
        $this->resetFields();
        $this->view = $view;
    }

    public function submit(): void
    {
        if ($this->view === 'login') {
            $this->login();
        } elseif ($this->view === 'register') {
            $this->register();
        }
    }

    public function login(): void
    {
        $this->loginForm->validate();

        $throttleKey = $this->throttleKey();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('auth_failed', "Çok fazla başarısız deneme. Lütfen {$seconds} saniye bekleyin.");

            return;
        }

        sleep(1); // Loading animasyonu için

        $credentials = [
            'email' => $this->loginForm->email,
            'password' => $this->loginForm->password,
        ];

        if (app(\App\Actions\Auth\LoginUserAction::class)->execute($credentials, $this->loginForm->remember, $throttleKey)) {
            $this->close();
            $this->dispatch('authUpdated');
            session()->flash('toast', ['type' => 'success', 'message' => 'Başarıyla giriş yapıldı. Hoş geldin!']);
            $this->redirect($this->getSafeRedirectUrl(), navigate: true);

            return;
        }

        $this->addError('auth_failed', 'E-posta adresi veya şifre hatalı.');
    }

    public function register(): void
    {
        $this->registerForm->validate();

        sleep(1); // Loading animasyonu için

        $data = [
            'username' => $this->registerForm->username,
            'email' => $this->registerForm->email,
            'password' => $this->registerForm->password,
        ];

        $user = app(\App\Actions\Auth\RegisterUserAction::class)->execute($data);

        Auth::login($user);
        session()->regenerate();

        $this->close();
        $this->dispatch('authUpdated');
        session()->flash('toast', ['type' => 'success', 'message' => 'Hesabın başarıyla oluşturuldu. Aramıza hoş geldin!']);
        $this->redirect($this->getSafeRedirectUrl(), navigate: true);
    }

    private function resetFields(): void
    {
        if (isset($this->loginForm)) {
            $this->loginForm->reset();
        }
        if (isset($this->registerForm)) {
            $this->registerForm->reset();
        }
        $this->forgotPasswordEmail = '';
    }

    private function throttleKey(): string
    {
        return 'auth-login:' . request()->ip();
    }

    private function getSafeRedirectUrl(): string
    {
        $referer = request()->header('Referer');
        $appUrl = config('app.url');

        if ($referer && str_starts_with($referer, $appUrl)) {
            return $referer;
        }

        return route('home');
    }
}; ?>

@php
    $current = $this->getConfig();
@endphp

<div x-data="{ isOpen: @entangle('isOpen') }" x-show="isOpen" x-on:keydown.escape.window="isOpen = false"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-all duration-300"
    x-cloak>
    <div class="bg-bg-dark w-full max-w-4xl h-auth-modal rounded-3xl overflow-hidden border border-white/10 shadow-2xl flex relative transition-all duration-300 transform"
        x-show="isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        @click.away="isOpen = false">
        {{-- Close Button --}}
        <button @click="isOpen = false"
            class="absolute top-4 right-4 z-20 w-10 h-10 rounded-full bg-black/50 hover:bg-primary text-white flex items-center justify-center transition-colors">
            <x-heroicon-o-x-mark class="w-6 h-6" />
        </button>

        {{-- Visual Section --}}
        <div class="hidden md:block w-5/12 relative overflow-hidden group">
            <img src="{{ $current['image'] }}" alt="{{ $current['imageAlt'] }}"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" />
            <div class="absolute inset-0 bg-gradient-to-t opacity-60 {{ $current['accent'] }}"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black/80"></div>
            <div class="absolute bottom-8 left-8 right-8 text-white z-10">
                <h3 class="text-3xl font-bold font-rubik leading-tight mb-2 drop-shadow-lg">{{ $current['title'] }}</h3>
                <p class="text-white/80 text-sm leading-relaxed">{{ $current['desc'] }}</p>
            </div>
        </div>

        {{-- Form Section --}}
        <div class="w-full md:w-7/12 p-8 md:p-12 flex flex-col justify-center relative bg-bg-dark">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-white mb-2 font-rubik">
                    {{ $current['formTitle'] }}
                </h2>
                <p class="text-white/40 text-sm">
                    {{ $current['formDesc'] }}
                </p>
            </div>

            {{-- Generic Error Alert --}}
            @if($errors->any())
                <div
                    class="mb-6 flex items-center gap-3 rounded-2xl bg-danger/10 p-4 border border-danger/20 animate-in fade-in slide-in-from-top-2 duration-300">
                    <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-danger" />
                    <p class="text-sm font-medium text-danger">
                        {{ $errors->first() }}
                    </p>
                </div>
            @endif

            <form wire:submit.prevent="submit" class="space-y-4">
                @if($view === 'register')
                    <div class="space-y-1">
                        <div class="relative">
                            <x-heroicon-o-user
                                class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-white/30 z-10" />
                            <x-ui.input wire:model="registerForm.username" type="text" placeholder="Kullanıcı Adı"
                                class="h-12 pl-12 {{ $errors->has('registerForm.username') ? 'border-danger/50' : '' }}" />
                        </div>
                    </div>
                @endif

                <div class="space-y-1">
                    <div class="relative">
                        <span
                            class="absolute left-4 top-1/2 -translate-y-1/2 text-white/30 z-10 font-bold text-lg">@</span>

                        @if($view === 'login')
                            <x-ui.input wire:model="loginForm.email" type="email" placeholder="E-posta Adresi"
                                class="h-12 pl-12 {{ $errors->has('loginForm.email') ? 'border-danger/50' : '' }}" />
                        @elseif($view === 'register')
                            <x-ui.input wire:model="registerForm.email" type="email" placeholder="E-posta Adresi"
                                class="h-12 pl-12 {{ $errors->has('registerForm.email') ? 'border-danger/50' : '' }}" />
                        @else
                            <x-ui.input wire:model="forgotPasswordEmail" type="email" placeholder="E-posta Adresi"
                                class="h-12 pl-12 {{ $errors->has('forgotPasswordEmail') ? 'border-danger/50' : '' }}" />
                        @endif
                    </div>
                </div>

                @if($view !== 'forgot-password')
                    <div class="space-y-1">
                        <div class="relative" x-data="{ show: false }">
                            <x-heroicon-o-lock-closed
                                class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-white/30 z-10" />

                            @if($view === 'login')
                                <x-ui.input wire:model="loginForm.password" ::type="show ? 'text' : 'password'"
                                    placeholder="Şifre"
                                    class="h-12 pl-12 pr-12 {{ $errors->has('loginForm.password') ? 'border-danger/50' : '' }}" />
                            @else
                                <x-ui.input wire:model="registerForm.password" ::type="show ? 'text' : 'password'"
                                    placeholder="Şifre"
                                    class="h-12 pl-12 pr-12 {{ $errors->has('registerForm.password') ? 'border-danger/50' : '' }}" />
                            @endif

                            <button type="button" @click="show = !show"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-white/30 hover:text-primary transition-colors z-10">
                                <x-heroicon-o-eye x-show="!show" class="w-5 h-5" />
                                <x-heroicon-o-eye-slash x-show="show" class="w-5 h-5" />
                            </button>
                        </div>
                    </div>
                @endif

                @if($view === 'login')
                    <div class="flex items-center gap-3 my-4">
                        <label class="relative inline-flex items-center cursor-pointer group">
                            <input wire:model="loginForm.remember" type="checkbox" class="sr-only peer" />
                            <div
                                class="w-10 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary border border-white/10 transition-colors duration-300 group-hover:bg-white/20 ease-in-out">
                            </div>
                            <span
                                class="ml-3 text-sm font-medium text-white/60 group-hover:text-white/90 transition-colors">Beni
                                hatırla</span>
                        </label>
                    </div>
                @endif

                <x-ui.button type="submit" variant="{{ $current['btnVariant'] }}" data-loading.attr="disabled"
                    class="w-full h-12 text-base font-bold mt-2 transition-all duration-300 data-loading:opacity-70 group/btn">
                    <span class="in-data-loading:hidden">
                        {{ $current['submitLabel'] }}
                    </span>
                    <div class="hidden in-data-loading:flex items-center justify-center gap-1.5 whitespace-nowrap">
                        <span>{{ $current['loadingLabel'] }}</span>
                        <div class="flex gap-1" data-loading>
                            <span class="animate-dot-bounce w-1.5 h-1.5 bg-current rounded-full"></span>
                            <span class="animate-dot-bounce delay-200 w-1.5 h-1.5 bg-current rounded-full"></span>
                            <span class="animate-dot-bounce delay-400 w-1.5 h-1.5 bg-current rounded-full"></span>
                        </div>
                    </div>
                    <x-heroicon-o-arrow-right class="w-5 h-5 ml-2 in-data-loading:hidden" />
                </x-ui.button>
            </form>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-white/5"></span>
                </div>
                <div class="relative flex justify-center text-xs uppercase"><span
                        class="bg-bg-dark px-2 text-white/40">veya</span></div>
            </div>

            <div class="text-center text-sm text-white/40">
                @if($view === 'login')
                    <div class="flex flex-col gap-3">
                        <div>
                            Hesabın yok mu? <button wire:click="setView('register')"
                                class="text-white hover:text-primary transition-colors font-bold">Kayıt Ol</button>
                        </div>
                        <button wire:click="setView('forgot-password')"
                            class="text-white/40 hover:text-primary transition-colors text-xs flex items-center justify-center gap-1">
                            Şifremi Unuttum
                        </button>
                    </div>
                @elseif($view === 'register')
                    Zaten hesabın var mı? <button wire:click="setView('login')"
                        class="text-white hover:text-primary transition-colors font-bold">Giriş Yap</button>
                @else
                    <button wire:click="setView('login')"
                        class="group text-white hover:text-primary transition-all duration-300 flex items-center justify-center gap-2 mx-auto font-medium">
                        <x-heroicon-o-arrow-left class="w-4 h-4 transition-transform group-hover:-translate-x-1" />
                        <span>Giriş Ekranına Dön</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>