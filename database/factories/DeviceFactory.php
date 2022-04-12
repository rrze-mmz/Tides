<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type_id'              => 1,
            'description'          => 'A device description',
            'comment'              => $this->faker->sentence(),
            'has_livestream_func'  => true,
            'opencast_device_name' => 'Opencast-CA',
            'name'                 => 'Opencast CA',
            'url'                  => $this->faker->url(),
            'telephone_number'     => $this->faker->phoneNumber(),
            'ip_address'           => $this->faker->ipv4(),
            'location_id'          => 2,
            'camera_url'           => $this->faker->url(),
            'power_outlet_url'     => $this->faker->url(),
            'organization_id'      => 1,
            'is_hybrid'            => true,
            'has_recording_func'   => true,
            'room_url'             => $this->faker->url(),
            'supervisor_id'        => 1,

        ];
    }
}
