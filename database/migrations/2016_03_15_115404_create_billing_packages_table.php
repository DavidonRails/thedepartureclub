<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBillingPackagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('billing_packages', function(Blueprint $table)
		{
			$table->increments('package_id');
			$table->string('name');
			$table->integer('price');
			$table->boolean('custom')->default(0);
			$table->integer('custom_user_id')->default(0);
			$table->boolean('status')->default(1);
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
		Schema::drop('billing_packages');
	}

}
