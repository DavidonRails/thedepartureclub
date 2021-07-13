<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFlightsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('flights', function(Blueprint $table)
		{
			$table->increments('flight_id');
			$table->string('flight_identification');
			$table->float('price', 10, 0)->nullable();
			$table->string('seats');
			$table->dateTime('flight_start');
			$table->dateTime('flight_end');
			$table->string('flight_time');
			$table->integer('aircraft_id');
			$table->integer('aircraft_image_id');
			$table->integer('user_id');
			$table->boolean('status')->default(1);
			$table->string('hash');
			$table->timestamps();
			$table->string('origin_location');
			$table->string('destination_location');
			$table->string('origin_lon');
			$table->string('origin_lat');
			$table->string('destination_lat');
			$table->string('destination_lon');
			$table->string('origin_airport_iata');
			$table->string('destination_airport_iata');
			$table->string('origin_airport_info');
			$table->string('destination_airport_info');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('flights');
	}

}
