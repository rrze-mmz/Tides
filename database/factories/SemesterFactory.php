<?php

namespace Database\Factories;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SemesterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Semester::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startSummerSemester = Carbon::now()->year.'-04-01 00:00:00';
        $endSummerSemester = Carbon::now()->year.'-09-30 23:59:59';
        if (Carbon::now()->between($startSummerSemester, $endSummerSemester)) {
            return [
                'name' => 'Sommerstemester '.Carbon::now()->year,
                'acronym' => 'S'.Carbon::now()->format('y'),
                'short_title' => Carbon::now()->year,
                'start_date' => Carbon::now()->year.'-04-01 00:00:00',
                'stop_date' => Carbon::now()->year.'-09-30 23:59:59',
            ];
        } elseif (Carbon::now()->isBefore($startSummerSemester)) {
            return [
                'name' => 'WinterSemester '.Carbon::now()->year - 1,
                'acronym' => 'W'.Carbon::now()->format('y') - 1,
                'short_title' => Carbon::now()->year,
                'start_date' => Carbon::now()->year - 1 .'-10-01 00:00:00',
                'stop_date' => Carbon::now()->year.'-03-31 23:59:59',
            ];
        } else {
            return [
                'name' => 'Wintersemester '.Carbon::now()->year.'/'.Carbon::now()->year + 1,
                'acronym' => 'W'.Carbon::now()->format('y'),
                'short_title' => Carbon::now()->year,
                'start_date' => Carbon::now()->year.'-10-01 00:00:00',
                'stop_date' => Carbon::now()->year + 1 .'-03-31 23:59:59',
            ];
        }
    }
}
