<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Datames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('datames', function($table){
            $table->increments('id');
            $table->string('fondo');
            $table->string('td');
            $table->string('x');
            $table->string('nomp');
            $table->string('fecnacimient');
            $table->string('dir');
            $table->string('dpto');
            $table->string('mnpio');
            $table->string('tp');
            $table->string('nbanco');
            $table->string('sucursal');
            $table->string('tel');
            $table->string('cel');
            $table->string('correo');
            $table->string('vpension');
            $table->string('vsalud');
            $table->string('vembargos');
            $table->string('vdesc');
            $table->string('cupo');
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
