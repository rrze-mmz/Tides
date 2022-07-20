<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //list from Videoportal
        $list = collect([
            '0' => '--- Ohne Titel---',
            '1' => 'Dr.',
            '2' => 'Prof.',
            '3' => 'Prof. Dr.',
            '4' => 'MA',
            '5' => 'Dipl.-Ing.',
            '6' => 'Dipl.-Inf.',
            '7' => 'PD Dr.',
            '8' => 'Dr. med.',
            '9' => 'Dipl.-Psych.',
            '10' => 'Dipl.-Kffr.',
            '11' => 'Dipl.-Kfm.',
            '12' => 'Dipl.-Math.',
            '13' => 'M. Sc.',
            '14' => 'Dr.-Ing.',
            '15' => 'M. Eng',
            '16' => 'Dipl.-Biol.',
            '17' => 'Prof. Dr.-Ing.',
            '18' => 'Prof. Dr. med. dent. ',
            '19' => 'Dipl.-Päd.',
            '20' => 'Dr. phil.',
            '21' => 'Prof. Dr. med.',
            '22' => 'Dr. rer. nat.',
            '23' => 'Prof. Dr. rer. nat.',
            '24' => 'Dr. med.',
            '25' => 'PD Dr. med.',
            '26' => 'Prof. Dr.-Ing. habil.',
            '27' => 'Dr. rer. biol. hum.',
            '28' => 'Dr. rer. pol.',
            '29' => 'AKD Dr. iur. Dr. phil.',
            '30' => 'Hon.-Prof. Dr.-Ing.',
            '31' => 'B. Sc.',
        ]);

        $list->each(function ($value, $key) {
            DB::table('academic_degrees')->insert([
                'id' => $key,
                'title' => $value,
                'created_at' => now(),
                'updated_at' => null,
            ]);
        });
    }
}
