<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTokensTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_tokens', function(Blueprint $table)
		{
			$table->increments('token_id');
			$table->integer('user_id');
			$table->string('token')->unique();
			$table->string('type');
			$table->string('created_by')->default('login');
			$table->boolean('active')->default(0);
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
		Schema::drop('users_tokens');
	}

}
