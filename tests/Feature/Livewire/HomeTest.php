<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\Pages\Home;
use Livewire\Livewire;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_can_render_home_page(): void
    {
        Livewire::test(Home::class)
            ->assertStatus(200);
    }

    public function test_home_page_route_works(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
