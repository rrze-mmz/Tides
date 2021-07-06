<?php

namespace Database\Factories;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'name'  => 'Wintersemester 2021/2022',
            'acronym'=> 'W21',
            'short_title'=> '2021/2022',
            'start_date'    => '2021-10-01 00:00:00',
            'stop_date'  => '2022-03-31 23:59:59',
        ];
    }
}
