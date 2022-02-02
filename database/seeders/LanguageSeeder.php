<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
            [
                'code'      => 'de',
                'name'      => 'Deutsch',
                'long_code' => 'de-DE',
                'order_int' => '1',
            ],
            [
                'code'      => 'en',
                'name'      => 'English',
                'long_code' => 'en-US',
                'order_int' => '2',
            ]
        ]);
    }
}
