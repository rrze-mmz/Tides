<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(array $types = [])
    {
        return [
            'user_id' => '1',
            'content_type' => 'series',
            'object_id' => 1,
            'change_message' => $this->faker->sentence(),
            'user_real_name' => 'John Doe',
            'action_flag' => 1,
            'changes' => [],
        ];
    }
}
