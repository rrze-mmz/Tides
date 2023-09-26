<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title_en' => $this->faker->sentence(),
            'content_en' => $this->faker->paragraph(5),
            'title_de' => $title = $this->faker->sentence(),
            'content_de' => $this->faker->paragraph(4),
            'slug' => Str::slug($title),
            'is_published' => true,
        ];
    }
}
