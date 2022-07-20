<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AclSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('acls')->insert(
            [
                'name' => 'public',
                'description' => 'Material is public available',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        );
        DB::table('acls')->insert(
            [
                'name' => 'portal',
                'description' => 'Material is accessed via portal log in',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        );
        DB::table('acls')->insert(
            [
                'name' => 'password',
                'description' => 'Material is accessed via a secret password (moodle, Ilias, etc.) log in ',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        );
        DB::table('acls')->insert(
            [
                'name' => 'lms',
                'description' => 'Material is accessed via LMS (moodle, Ilias, etc.) log in',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        );
        DB::table('acls')->insert(
            [
                'name' => 'other',
                'description' => 'Other acls',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        );
    }
}
