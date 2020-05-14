<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProtocoloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocolo', function (Blueprint $table) {
            $table->increments('id_protocolo');
            $table->string('protocolo',20);
            $table->dateTime('data');
            $table->integer('id_cliente');
            $table->integer('id_usuario_cad');
            $table->integer('id_setor');
            $table->string('email',255);
            $table->string('solicitante',100);
            $table->text('solicitacao')->nullable();
            $table->text('arquivo')->nullable();
            $table->string('placa',50);
            $table->integer('id_veiculo')->nullable();
            $table->string('arquivo_type',30)->nullable();
            $table->smallInteger('status');
            $table->dateTime('dum')->nullable();
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
        Schema::drop('protocolo');
    }
}
