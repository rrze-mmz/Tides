<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'opencast',
            'data' => [
                'url' => 'localhost:8080',
                'username' => 'admin',
                'password' => 'opencast',
                'archive_path' => '/archive/mh_default',
                'default_workflow_id' => 'fast',
                'upload_workflow_id' => 'fast',
                'theme_id_top_right' => '500',
                'theme_id_top_left' => '501',
                'theme_id_bottom_left' => '502',
                'theme_id_bottom_right' => '502',
            ],
        ];
    }
}
