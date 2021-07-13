<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewBookingsPending extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings_pending', function(Blueprint $table)
        {
            $table->increments('booking_pending_id');
            $table->integer('user_id');
            $table->integer('flight_id');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->dateTime( 'departure_start' );
            $table->dateTime( 'departure_end' );
            $table->longText( 'passengers' );
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
