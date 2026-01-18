<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function execute(array $data): User
    {
        $user = User::create([
            'name' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // User observer may create a profile, but we ensure specific data is set
        $user->profile()->update([
            'username' => $data['username'],
        ]);

        return $user;
    }
}
