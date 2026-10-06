<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Artikel;
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
        $this->call(CategorySeeder::class);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Ruang',
                'password' => bcrypt('password'),
                'is_admin' => true,
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Arga Pratama',
                'password' => bcrypt('password'),
                'is_admin' => false,
            ]
        );

        $categoryIds = Category::pluck('id')->all();

        Artikel::factory(12)->create([
            'user_id' => $user->id,
            'category_id' => fake()->randomElement(array_merge([null], $categoryIds)),
        ]);
    }
}
