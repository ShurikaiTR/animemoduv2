<?php

declare(strict_types=1);

namespace App\Livewire\Layout;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class NavbarSearch extends Component
{
    public string $query = '';

    public bool $isOpen = false;

    public bool $showResults = false;

    public function updatedQuery(): void
    {
        if (strlen($this->query) >= 2) {
            $this->showResults = true;
        } else {
            $this->showResults = false;
        }
    }

    public function toggleSearch(): void
    {
        $this->isOpen = ! $this->isOpen;
        if (! $this->isOpen) {
            $this->query = '';
            $this->showResults = false;
        }
    }

    public function render(): View
    {
        return view('livewire.layout.navbar-search');
    }
}
