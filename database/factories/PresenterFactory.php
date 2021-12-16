<?php

namespace Database\Factories;

use App\Models\Presenter;
use Illuminate\Database\Eloquent\Factories\Factory;

class PresenterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Presenter::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'first_name'   => $this->faker->firstName(),
            'last_name'    => $this->faker->lastName(),
            'degree_title' => 'Dr.',
            'username'     => $this->faker->unique()->userName(),
            'email'        => $this->faker->unique()->safeEmail(),
        ];
    }
}
