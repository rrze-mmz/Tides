<?php

namespace Database\Factories;

use App\Models\Clip;
use App\Models\Context;
use App\Models\Format;
use App\Models\Language;
use App\Models\Organization;
use App\Models\Semester;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Clip::class;

    /**
     * Define the model's default state
     *
     * @return array
     */
    public function definition(): array
    {
        static $episode = 0;

        return [
            'title'           => $title = $this->faker->sentence(),
            'description'     => $this->faker->paragraph(),
            'slug'            => $title,
            'organization_id' => Organization::factory()->create()->org_id,
            'language_id'     => Language::factory()->create()->id,
            'context_id'      => Context::factory()->create()->id,
            'format_id'       => Format::factory()->create()->id,
            'type_id'         => Type::factory()->create()->id,
            'owner_id'        => User::factory()->create()->id,
            'semester_id'     => Semester::factory()->create()->id,
            'posterImage'     => null,
            'series_id'       => null,
            'episode'         => $episode++,
            'isPublic'        => true,
        ];
    }
}
