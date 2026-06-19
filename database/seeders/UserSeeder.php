<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 15 fake users
        User::factory(15)->create();

        // Create a specific admin test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create an unverified user
        User::factory()->unverified()->create([
            'name' => 'Unverified User',
            'email' => 'unverified@example.com',
        ]);
    }
}
