<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('semesters')->insert([
            'name'        => 'Sommersemester 2021',
            'acronym'     => 'S21',
            'short_title' => '2021',
            'start_date'  => '2021-04-01 00:00:00',
            'stop_date'   => '2022-09-30 23:59:59',
        ]);

        DB::table('semesters')->insert([
            'name'        => 'Wintersemester 2021/2022',
            'acronym'     => 'W21',
            'short_title' => '2021/2022',
            'start_date'  => '2021-10-01 00:00:00',
            'stop_date'   => '2022-03-31 23:59:59',
        ]);
    }
}
