<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class RegisterForm extends Form
{
    #[Validate('required', message: 'Kullanıcı adı zorunludur.', onUpdate: false)]
    #[Validate('string', onUpdate: false)]
    #[Validate('min:3', message: 'Kullanıcı adı en az 3 karakter olmalıdır.', onUpdate: false)]
    #[Validate('max:20', message: 'Kullanıcı adı en fazla 20 karakter olmalıdır.', onUpdate: false)]
    #[Validate('unique:profiles,username', message: 'Bu kullanıcı adı zaten alınmış.', onUpdate: false)]
    public string $username = '';

    #[Validate('required', message: 'E-posta adresi zorunludur.', onUpdate: false)]
    #[Validate('email', message: 'Geçerli bir e-posta adresi giriniz.', onUpdate: false)]
    #[Validate('unique:users,email', message: 'Bu e-posta adresi zaten kayıtlı.', onUpdate: false)]
    public string $email = '';

    #[Validate('required', message: 'Şifre zorunludur.', onUpdate: false)]
    #[Validate('string', onUpdate: false)]
    #[Validate('min:8', message: 'Şifre en az 8 karakter olmalıdır.', onUpdate: false)]
    public string $password = '';
}
