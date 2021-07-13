<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->increments('notification_id');
			$table->integer('sender_user_id');
			$table->text('message', 65535);
			$table->boolean('status')->default(1);
			$table->dateTime('date_readed');
			$table->timestamps();
			$table->integer('type');
			$table->text('data', 16777215);
			$table->integer('parent_id')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notifications');
	}

}
