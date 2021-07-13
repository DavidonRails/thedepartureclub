<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBookingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bookings', function(Blueprint $table)
		{
			$table->increments('booking_id');
			$table->integer('user_id');
			$table->integer('flight_id');
			$table->boolean('status')->default(0);
			$table->dateTime('time_requested');
			$table->dateTime('time_approved');
			$table->timestamps();
			$table->string('departure_time_start');
			$table->string('departure_time_end');
			$table->string('departure_time_final');
			$table->string('tail_number');
			$table->string('price');
			$table->integer('payment_id');
			$table->string('fbo');
			$table->dateTime('arriving_date_time');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bookings');
	}

}
