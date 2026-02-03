<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use Livewire\Livewire;
use Tests\TestCase;

class AuthModalTest extends TestCase
{
    public function test_can_render_auth_modal(): void
    {
        Livewire::test('auth.auth-modal')
            ->assertStatus(200);
    }

    public function test_modal_starts_closed(): void
    {
        Livewire::test('auth.auth-modal')
            ->assertSet('isOpen', false)
            ->assertSet('view', 'login');
    }

    public function test_can_open_modal(): void
    {
        Livewire::test('auth.auth-modal')
            ->dispatch('openAuthModal')
            ->assertSet('isOpen', true);
    }

    public function test_can_open_modal_with_register_view(): void
    {
        Livewire::test('auth.auth-modal')
            ->dispatch('openAuthModal', view: 'register')
            ->assertSet('isOpen', true)
            ->assertSet('view', 'register');
    }

    public function test_can_close_modal(): void
    {
        Livewire::test('auth.auth-modal')
            ->dispatch('openAuthModal')
            ->call('close')
            ->assertSet('isOpen', false);
    }

    public function test_can_switch_views(): void
    {
        Livewire::test('auth.auth-modal')
            ->call('setView', 'register')
            ->assertSet('view', 'register')
            ->call('setView', 'forgot-password')
            ->assertSet('view', 'forgot-password')
            ->call('setView', 'login')
            ->assertSet('view', 'login');
    }
}
