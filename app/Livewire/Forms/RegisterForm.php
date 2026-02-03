<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class RegisterForm extends Form
{
    #[Validate('required|string|min:3|max:20|unique:profiles,username', message: [
        'required' => 'Kullanıcı adı zorunludur.',
        'min' => 'Kullanıcı adı en az 3 karakter olmalıdır.',
        'max' => 'Kullanıcı adı en fazla 20 karakter olmalıdır.',
        'unique' => 'Bu kullanıcı adı zaten alınmış.'
    ], onUpdate: false)]
    public string $username = '';

    #[Validate('required|email|unique:users,email', message: [
        'required' => 'E-posta adresi zorunludur.',
        'email' => 'Geçerli bir e-posta adresi giriniz.',
        'unique' => 'Bu e-posta adresi zaten kayıtlı.'
    ], onUpdate: false)]
    public string $email = '';

    #[Validate('required|string|min:8', message: [
        'required' => 'Şifre zorunludur.',
        'min' => 'Şifre en az 8 karakter olmalıdır.'
    ], onUpdate: false)]
    public string $password = '';
}
