<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAlertsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('alerts', function(Blueprint $table)
		{
			$table->increments('alert_id');
			$table->integer('user_id');
			$table->string('origin');
			$table->string('destination');
			$table->smallInteger('active');
			$table->timestamps();
			$table->string('origin_longitude');
			$table->string('origin_latitude');
			$table->string('destination_longitude');
			$table->string('destination_latitude');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('alerts');
	}

}
