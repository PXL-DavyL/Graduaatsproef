<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\BlogReaction;
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

        // Create users
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@test.net',
            'password' => bcrypt('test@test.net'),
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.net',
            'password' => bcrypt('admin@test.net'),
        ]);
        $admin->assignRole('admin');

        // Create blogs
        Blog::factory(25)->create(); 

        // Create blog comments and reactions
        Blog::all()->each(function ($blog) {
            $blog->update(['views' => rand(5, 1000)]);

            BlogComment::factory(rand(5, 25))->create([
                'blog_id' => $blog->id,
                'poster_id' => function () {
                    return User::inRandomOrder()->first()->id;
                },
            ]);

            BlogReaction::factory(rand(5, 25))->create([
                'blog_id' => $blog->id,
                'poster_id' => function () {
                    return User::inRandomOrder()->first()->id;
                },
            ]);
        });
    }
}
