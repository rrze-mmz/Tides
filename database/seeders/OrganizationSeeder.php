<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organizations')->insert([
            'org_id'             => 1,
            'name'               => 'Tides organization unit',
            'parent_org_id'      => 1,
            'orgno'              => '0000000000',
            'shortname'          => 'Main organization unit',
            'staff'              => null,
            'startdate'          => now(),
            'enddate'            => '2999-12-31',
            'operationstartdate' => now(),
            'operationenddate'   => '2999-12-31',
            'created_at'         => now(),
            'updated_at'         => null,
        ]);
    }
}
