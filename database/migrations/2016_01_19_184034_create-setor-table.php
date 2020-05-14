<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setor', function (Blueprint $table) {
            $table->increments('id_setor');
            $table->string('nome',50);
            $table->string('email',255);
            $table->string('responsavel',255)->nullable();;
            $table->integer('tempo')->nullable();;
            $table->smallInteger('status');
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
        Schema::drop('setor');
    }
}
