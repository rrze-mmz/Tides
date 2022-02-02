<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Organization::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'               => 'Tides organization unit',
            'parent_org_id'      => 1,
            'orgno'              => '0000000000',
            'shortname'          => 'Main organization unit',
            'staff'              => null,
            'startdate'          => now(),
            'operationstartdate' => now(),
            'operationenddate'   => '2999-12-31',
            'created_at'         => now(),
            'updated_at'         => null,
        ];
    }
}
