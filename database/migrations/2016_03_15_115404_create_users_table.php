<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('user_id');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('company')->nullable();
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->integer('status')->default(2);
			$table->string('role')->default('user');
			$table->string('type')->nullable();
			$table->string('confirmation_code')->nullable();
			$table->bigInteger('facebook_id')->unsigned()->nullable()->index();
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
			$table->text('pending_data', 65535);
			$table->string('phone');
			$table->string('address');
			$table->string('city');
			$table->string('state');
			$table->string('country');
			$table->string('timezone');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
