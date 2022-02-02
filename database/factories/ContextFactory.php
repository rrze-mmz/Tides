<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContextFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'de_name' => 'Collegium  Alexandrinum',
            'en_name' => 'Collegium Alexandrinum'
        ];
    }
}
