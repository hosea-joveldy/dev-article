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

        $categoryIds = Category::pluck('id')->all();

        Artikel::factory(100)->create([
            'category_id' => fake()->randomElement(array_merge([null], $categoryIds)),
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
