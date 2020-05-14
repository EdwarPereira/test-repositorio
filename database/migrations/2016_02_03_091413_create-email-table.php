<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_protocolo');
            $table->string('from',100);
            $table->string('fromname',100);
            $table->string('to',200);
            $table->string('toname',200);
            $table->string('replyto',100);
            $table->string('replytoname',100);
            $table->string('subject',255);
            $table->text('body');
            $table->text('anexo')->nullable();
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
        Schema::drop('email');
    }
}
