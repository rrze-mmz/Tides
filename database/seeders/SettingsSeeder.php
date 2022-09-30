<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'name' => 'portal',
            'data' => json_encode([
                'maintenance_mode' => false,
            ]),
        ]);

        DB::table('settings')->insert([
            'name' => 'opencast',
            'data' => json_encode([]),
        ]);

        DB::table('settings')->insert([
            'name' => 'streaming',
            'data' => json_encode([]),
        ]);
    }
}
