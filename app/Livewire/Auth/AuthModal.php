<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Livewire\Auth\Concerns\HasAuthModalConfig;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\On;
use Livewire\Component;

class AuthModal extends Component
{
    use HasAuthModalConfig;

    public string $view = 'login'; // 'login', 'register', 'forgot-password'

    public bool $isOpen = false;

    public string $email = '';

    public string $username = '';

    public string $password = '';

    public bool $remember = false;

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
        $throttleKey = $this->throttleKey();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('auth_failed', "Çok fazla başarısız deneme. Lütfen {$seconds} saniye bekleyin.");

            return;
        }

        sleep(1); // Loading animasyonu için

        $credentials = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (app(\App\Actions\Auth\LoginUserAction::class)->execute($credentials, $this->remember, $throttleKey)) {
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
        sleep(1); // Loading animasyonu için

        $data = $this->validate([
            'username' => ['required', 'string', 'min:3', 'max:20', 'unique:profiles,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

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
        $this->email = '';
        $this->username = '';
        $this->password = '';
        $this->remember = false;
    }

    private function throttleKey(): string
    {
        return 'auth-login:'.request()->ip();
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

    public function render(): View
    {
        return view('livewire.auth.auth-modal');
    }
}
