<?php

declare(strict_types=1);

namespace App\Livewire\Layout;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserMenu extends Component
{
    public function logout(): void
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('home'), navigate: true);
    }

    public function render()
    {
        $user = Auth::user();
        $profile = $user?->profile;

        return view('livewire.layout.user-menu', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }
}
