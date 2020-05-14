<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('placa_gt', function (Blueprint $table) {
            $table->increments('vehicle_id');
            $table->integer('customer_id');
            $table->string('licplate',8);
            $table->smallInteger('active');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('placa_gt');
    }
}
