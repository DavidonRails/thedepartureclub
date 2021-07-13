<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFlightPassengersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('flight_passengers', function(Blueprint $table)
		{
			$table->increments('passenger_id');
			$table->integer('flight_id');
			$table->integer('booking_id');
			$table->integer('user_id')->default(0);
			$table->boolean('status')->default(0);
			$table->timestamps();
			$table->string('first_name');
			$table->string('last_name');
			$table->date('birth_date');
			$table->string('weight');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('flight_passengers');
	}

}
