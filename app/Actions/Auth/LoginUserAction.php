<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginUserAction
{
    public function execute(array $credentials, bool $remember, string $throttleKey): bool
    {
        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($throttleKey);
            session()->regenerate();

            return true;
        }

        RateLimiter::hit($throttleKey, 60);

        return false;
    }
}
