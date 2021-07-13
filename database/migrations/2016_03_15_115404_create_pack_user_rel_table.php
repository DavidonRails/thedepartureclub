<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePackUserRelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pack_user_rel', function(Blueprint $table)
		{
			$table->increments('pack_user_rel_id');
			$table->boolean('status')->default(1);
			$table->boolean('owner')->default(0);
			$table->integer('pack_id');
			$table->integer('user_id');
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
		Schema::drop('pack_user_rel');
	}

}
