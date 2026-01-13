<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Livewire\Auth\Concerns\HasAuthModalConfig;

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
        sleep(1);
        $credentials = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();

            $this->close();
            $this->dispatch('authUpdated');
            session()->flash('toast', ['type' => 'success', 'message' => 'Başarıyla giriş yapıldı. Hoş geldin!']);
            $this->redirect(request()->header('Referer') ?? route('home'), navigate: true);

            return;
        }

        $this->addError('auth_failed', 'E-posta adresi veya şifre hatalı.');
    }

    public function register(): void
    {
        sleep(1);
        $data = $this->validate([
            'username' => ['required', 'string', 'min:3', 'max:20', 'unique:profiles,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // User observer otomatik profil oluşturduğu için mevcut profili güncelliyoruz
        $user->profile()->update([
            'username' => $data['username'],
        ]);

        Auth::login($user);
        session()->regenerate();

        $this->close();
        $this->dispatch('authUpdated');
        session()->flash('toast', ['type' => 'success', 'message' => 'Hesabın başarıyla oluşturuldu. Aramıza hoş geldin!']);
        $this->redirect(request()->header('Referer') ?? route('home'), navigate: true);
    }

    private function resetFields(): void
    {
        $this->email = '';
        $this->username = '';
        $this->password = '';
        $this->remember = false;
    }

    public function render(): View
    {
        return view('livewire.auth.auth-modal');
    }
}
