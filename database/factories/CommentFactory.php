<?php

namespace Database\Factories;

use App\Models\Clip;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content'  => $this->faker->paragraph(),
            'clip_id'  => Clip::factory()->create()->id,
            'owner_id' => User::factory()->create()->id
        ];
    }
}
