<?php

namespace Database\Factories;

use App\Models\AcademicDegree;
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
            'academic_degree_id' => '1',
            'first_name'         => $this->faker->firstName(),
            'last_name'          => $this->faker->lastName(),
            'username'           => $this->faker->unique()->userName(),
            'email'              => $this->faker->unique()->safeEmail(),
        ];
    }
}
