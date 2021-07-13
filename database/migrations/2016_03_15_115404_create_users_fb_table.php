<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersFbTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_fb', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->bigInteger('facebook_id')->unsigned()->nullable()->index();
			$table->text('token', 65535);
			$table->text('data', 65535);
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
		Schema::drop('users_fb');
	}

}
