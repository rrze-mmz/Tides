<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            RoleUserSeeder::class,
            AclSeeder::class,
            OrganizationSeeder::class,
            SemesterSeeder::class,
            DocDataSeeder::class,
            LanguageSeeder::class,
        ]);
    }
}
