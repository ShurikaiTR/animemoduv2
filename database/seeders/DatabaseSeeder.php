<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'Gokhan123.',
        ]);

        $admin->profile()->create([
            'username' => 'admin',
            'full_name' => 'Admin User',
            'role' => \App\Enums\UserRole::ADMIN->value,
        ]);
    }
}
