<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required', message: 'E-posta adresi zorunludur.', onUpdate: false)]
    #[Validate('email', message: 'Geçerli bir e-posta adresi giriniz.', onUpdate: false)]
    public string $email = '';

    #[Validate('required', message: 'Şifre alanı zorunludur.', onUpdate: false)]
    #[Validate('string', onUpdate: false)]
    #[Validate('min:8', message: 'Şifre en az :min karakter olmalıdır.', onUpdate: false)]
    public string $password = '';

    public bool $remember = false;
}
