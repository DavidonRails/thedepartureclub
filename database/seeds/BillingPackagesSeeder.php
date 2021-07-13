<?php

use Illuminate\Database\Seeder;

class BillingPackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('billing_packages')->insert([
//            'package_id' => 1,
            'name' => 'Tier 1',
            'price' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('billing_packages')->insert([
//            'package_id' => 2,
            'name' => 'Tier 2',
            'price' => 4,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        DB::table('billing_packages')->insert([
//            'package_id' => 3,
            'name' => 'Tier 3',
            'price' => 14,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
