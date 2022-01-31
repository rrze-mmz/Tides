<?php

namespace Database\Factories;

use App\Models\AcademicDegree;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicDegreeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AcademicDegree::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => 'Dr. '
        ];
    }
}
