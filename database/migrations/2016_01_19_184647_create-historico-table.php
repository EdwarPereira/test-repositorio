<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico', function (Blueprint $table) {
            $table->increments('id_historico');
            $table->integer('status');
            $table->integer('id_protocolo');
            $table->smallInteger('id_setor');
            $table->smallInteger('id_setor_anterior')->nullable();
            $table->dateTime('data');
            $table->dateTime('previsao')->nullable();
            $table->text('observacao');
            $table->integer('id_usuario')->nullable();
            $table->text('arquivo')->nullable();
            $table->string('arquivo_type',30)->nullable();
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
        Schema::drop('historico');
    }
}
