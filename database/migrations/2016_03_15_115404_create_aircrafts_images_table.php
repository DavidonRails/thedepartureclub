<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAircraftsImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('aircrafts_images', function(Blueprint $table)
		{
			$table->increments('image_id');
			$table->integer('aircraft_id');
			$table->string('name');
			$table->string('path');
			$table->boolean('default')->default(0);
			$table->boolean('active')->default(1);
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('aircrafts_images');
	}

}
