<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PasswordLoginAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_login', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('password');
			$table->timestamps();
		});

         DB::table('password_login')->insert(['password' => md5('loodloo')]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('password_login');
    }
}
