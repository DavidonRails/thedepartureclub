<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBookingsPaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bookings_payments', function(Blueprint $table)
		{
			$table->increments('booking_payment_id');
			$table->integer('booking_id');
			$table->boolean('status')->default(0);
			$table->text('data', 16777215);
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
		Schema::drop('bookings_payments');
	}

}
