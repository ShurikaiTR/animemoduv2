<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|email', message: [
        'required' => 'E-posta adresi zorunludur.',
        'email' => 'Geçerli bir e-posta adresi giriniz.',
    ], onUpdate: false)]
    public string $email = '';

    #[Validate('required|string|min:8', message: [
        'required' => 'Şifre alanı zorunludur.',
        'min' => 'Şifre en az :min karakter olmalıdır.',
    ], onUpdate: false)]
    public string $password = '';

    public bool $remember = false;
}
