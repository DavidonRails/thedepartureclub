<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsRelationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications_relations', function(Blueprint $table)
		{
			$table->increments('notification_rel_id');
			$table->integer('notification_id');
			$table->timestamps();
			$table->boolean('status')->default(1);
			$table->integer('user_id')->default(0);
			$table->string('device_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notifications_relations');
	}

}
