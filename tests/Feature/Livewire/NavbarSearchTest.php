<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\Layout\NavbarSearch;
use Livewire\Livewire;
use Tests\TestCase;

class NavbarSearchTest extends TestCase
{
    public function test_can_render_navbar_search(): void
    {
        Livewire::test(NavbarSearch::class)
            ->assertStatus(200);
    }

    public function test_search_starts_closed(): void
    {
        Livewire::test(NavbarSearch::class)
            ->assertSet('isOpen', false)
            ->assertSet('query', '')
            ->assertSet('showResults', false);
    }

    public function test_can_toggle_search(): void
    {
        Livewire::test(NavbarSearch::class)
            ->call('toggleSearch')
            ->assertSet('isOpen', true)
            ->call('toggleSearch')
            ->assertSet('isOpen', false)
            ->assertSet('query', '');
    }

    public function test_shows_results_when_query_has_two_or_more_chars(): void
    {
        Livewire::test(NavbarSearch::class)
            ->set('query', 'a')
            ->assertSet('showResults', false)
            ->set('query', 'an')
            ->assertSet('showResults', true)
            ->set('query', 'anime')
            ->assertSet('showResults', true);
    }

    public function test_hides_results_when_query_is_cleared(): void
    {
        Livewire::test(NavbarSearch::class)
            ->set('query', 'naruto')
            ->assertSet('showResults', true)
            ->set('query', '')
            ->assertSet('showResults', false);
    }
}
