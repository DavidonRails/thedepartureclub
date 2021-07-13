<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAircraftsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('aircrafts', function(Blueprint $table)
		{
			$table->increments('aircraft_id');
			$table->string('name');
			$table->string('manufacturer');
			$table->string('seats');
			$table->boolean('active')->default(1);
			$table->timestamps();
			$table->boolean('type')->default(0);
			$table->string('flight_price')->default('0');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('aircrafts');
	}

}
