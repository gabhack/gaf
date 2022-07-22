<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCifin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_cifin', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('usuarioid')->references('id')->on('users');
            $table->integer('idtransaccion')->nullable();
            $table->string('nombre', 250)->nullable();
            $table->string('apellido', 200)->nullable();
            $table->string('cedula', 10)->nullable();
            $table->string('resputa', 2)->nullable();
            $table->string('status', 250);
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
        Schema::dropIfExists('table_cifin');
    }
}
