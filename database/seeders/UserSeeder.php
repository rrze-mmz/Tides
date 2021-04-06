<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'John Doe',
            'email' => 'user@test.com',
            'password' => Hash::make('12341234'),
        ]);

        DB::table('users')->insert([
            'name' => 'John Doe',
            'email' => 'admin@test.com',
            'password' => Hash::make('12341234'),
        ]);
    }
}
