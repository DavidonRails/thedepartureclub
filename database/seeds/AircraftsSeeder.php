<?php

use Illuminate\Database\Seeder;

class AircraftsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $aircrafts = new \App\Models\Aircrafts();
        $aircrafts->add([
            'name' => 'Gulfstream',
            'manufacturer' => 'Gulfstream',
            'seats' => 5
        ]);

        $aircrafts = new \App\Models\Aircrafts();
        $aircrafts->add([
            'name' => 'Cesna',
            'manufacturer' => 'Cesna',
            'seats' => 5
        ]);

        $aircrafts = new \App\Models\Aircrafts();
        $aircrafts->add([
            'name' => 'Hawker',
            'manufacturer' => 'Hawker',
            'seats' => 5
        ]);

        $aircrafts = new \App\Models\Aircrafts();
        $aircrafts->add([
            'name' => 'Dassault',
            'manufacturer' => 'Dassault',
            'seats' => 5
        ]);

    }
}
