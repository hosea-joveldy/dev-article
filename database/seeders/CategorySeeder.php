<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Technology',
            'Tutorial',
            'Programming',
            'Opinion',
            'News',
            'Tips & Tricks',
        ];

        foreach ($names as $nama) {
            Category::firstOrCreate(
                ['slug' => Str::slug($nama)],
                ['nama' => $nama]
            );
        }
    }
}
