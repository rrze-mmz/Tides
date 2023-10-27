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
            'data' => json_encode(config('settings.portal')),
        ]);

        DB::table('settings')->insert([
            'name' => 'opencast',
            'data' => json_encode(config('settings.opencast')),
        ]);

        DB::table('settings')->insert([
            'name' => 'streaming',
            'data' => json_encode(config('settings.streaming')),
        ]);

        DB::table('settings')->insert([
            'name' => 'openSearch',
            'data' => json_encode(config('settings.openSearch')),
        ]);
    }
}
