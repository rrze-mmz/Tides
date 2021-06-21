<?php

namespace Database\Factories;

use App\Models\Acl;
use Illuminate\Database\Eloquent\Factories\Factory;

class AclFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Acl::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
                'name' => $this->faker->text(),
                'description'  => $this->faker->sentence(),
        ];
    }
}
