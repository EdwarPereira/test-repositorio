<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContatoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contato_gt', function (Blueprint $table) {
            $table->increments('contact_id');
            $table->string('name',255);
            $table->string('email',255);
            $table->smallInteger('send_protocol');
            $table->integer('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contato_gt');
    }
}
