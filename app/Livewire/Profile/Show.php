<?php

declare(strict_types=1);

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

class Show extends Component
{
    #[Locked]
    public User $user;

    #[Url]
    public string $activeTab = 'all';

    public function mount(?string $username = null): void
    {
        if (! $username) {
            if (! auth()->check()) {
                $this->redirectRoute('login');

                return;
            }
            $this->user = auth()->user();
        } else {
            $this->user = User::where('name', $username)
                ->orWhereHas('profile', fn ($q) => $q->where('username', $username))
                ->firstOrFail();
        }

        $this->user->load(['profile', 'favorites', 'activities']);
    }

    #[Computed]
    public function watchlist(): Collection
    {
        return $this->user->watchlist()
            ->with('anime')
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    #[Computed]
    public function favorites(): Collection
    {
        return $this->user->favorites()
            ->with('favoritable')
            ->get();
    }

    #[Computed]
    public function activities(): Collection
    {
        return $this->user->activities()
            ->latest()
            ->limit(10)
            ->get();
    }

    #[Layout('components.layout.app')]
    #[Title('Profil')]
    public function render(): View
    {
        return view('livewire.profile.show', [
            'watchlist' => $this->watchlist,
            'favorites' => $this->favorites,
            'activities' => $this->activities,
        ]);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function toggleFollow(): void
    {
        if (! auth()->check()) {
            $this->redirectRoute('login');

            return;
        }

        // TODO: Implement follow logic once Follow system is ready
        // $this->user->toggleFollow(auth()->id());
    }
}
