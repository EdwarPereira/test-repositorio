<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioGtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_gt', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('username',255);
            $table->string('display_name',255);
            $table->string('password',128);
            $table->smallInteger('state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('usuario_gt');
    }
}
