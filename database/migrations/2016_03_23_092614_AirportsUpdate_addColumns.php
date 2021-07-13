<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AirportsUpdateAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('airports', function(Blueprint $table)
        {

            $table->string('icao')->nullable();
            $table->string('iata')->nullable();
            $table->string('name')->nullable();
            $table->string('location')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
