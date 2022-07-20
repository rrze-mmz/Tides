<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FormatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'en_name' => 'lecture',
            'de_name' => 'Vorlesung',
        ];
    }
}
