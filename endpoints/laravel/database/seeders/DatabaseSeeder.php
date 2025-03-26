<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create permissions
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@test.net',
            'password' => bcrypt('test@test.net'),
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.net',
            'password' => bcrypt('admin@test.net'),
        ]);
        $admin->assignRole('admin');

        User::factory(100)->create();
    }
}
