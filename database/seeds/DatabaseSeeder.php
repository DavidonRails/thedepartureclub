<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UserSeeder::class);
        $this->call(BillingPackagesSeeder::class);
        $this->call(FlightsSeeder::class);
        $this->call(BookingsSeeder::class);

        Model::reguard();
    }
}