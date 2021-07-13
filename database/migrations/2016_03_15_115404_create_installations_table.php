<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInstallationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('installations', function(Blueprint $table)
		{
			$table->increments('installation_id');
			$table->integer('user_id')->default(0);
			$table->string('ios_token');
			$table->timestamps();
			$table->string('device_id');
			$table->integer('send_notifications')->default(1);
			$table->integer('status')->default(1);
			$table->string('hash');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('installations');
	}

}
