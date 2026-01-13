<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_profile_is_created_automatically_for_new_user()
    {
        $user = \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->assertDatabaseHas('profiles', [
            'user_id' => $user->id,
            'username' => 'test',
            'full_name' => 'Test User',
        ]);

        $this->assertNotNull($user->profile);
        $this->assertEquals('test', $user->profile->username);
    }

    public function test_user_can_check_admin_status_via_profile()
    {
        $user = \App\Models\User::factory()->create();

        $this->assertFalse($user->isAdmin());

        $user->profile->update(['role' => 'admin']);

        // Refresh to get updated profile data
        $user->refresh();

        $this->assertTrue($user->isAdmin());
    }
}
