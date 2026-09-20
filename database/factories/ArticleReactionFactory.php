<?php

namespace Database\Factories;

use App\Models\ArticleReaction;
use App\Models\Artikel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ArticleReaction>
 */
class ArticleReactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'artikel_id' => Artikel::factory(),
            'user_id' => User::factory(),
            'value' => fake()->randomElement([ArticleReaction::LIKE, ArticleReaction::DISLIKE]),
        ];
    }
}
