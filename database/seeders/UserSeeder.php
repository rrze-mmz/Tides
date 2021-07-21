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
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'username'   => 'user1',
            'email'      => 'user1@test.com',
            'password'   => Hash::make('12341234'),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Alice',
            'last_name'  => 'Doe',
            'username'   => 'user2',
            'email'      => 'user2@test.com',
            'password'   => Hash::make('12341234'),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Bob',
            'last_name'  => 'the Admin',
            'username'   => 'admin',
            'email'      => 'admin@test.com',
            'password'   => Hash::make('12341234'),
        ]);
    }
}
