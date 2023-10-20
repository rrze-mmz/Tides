<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

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
            SettingsSeeder::class,
            ArticleSeeder::class,
        ]);
    }
}
