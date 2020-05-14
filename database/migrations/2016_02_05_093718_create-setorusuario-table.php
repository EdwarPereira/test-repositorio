<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetorusuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setorusuario', function (Blueprint $table) { // clase "Schema" para crear la base de datos: 'setorusuario'
            $table->increments('id');
            $table->integer('id_usuario');
            $table->integer('id_setor');
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
        Schema::drop('setorusuario');
    }
}
