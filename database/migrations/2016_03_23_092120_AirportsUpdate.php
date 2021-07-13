<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AirportsUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('airports', function(Blueprint $table)
        {

            $table->dropColumn('ident');
            $table->dropColumn('name');
            $table->dropColumn('municipality');
            $table->dropColumn('state');
            $table->dropColumn('country');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
