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
            $table->string('fondo')->nullable();
            $table->string('td')->nullable();
            $table->string('x')->nullable();
            $table->string('nomp')->nullable();
            $table->string('fecnacimient')->nullable();
            $table->string('dir')->nullable();
            $table->string('dpto')->nullable();
            $table->string('mnpio')->nullable();
            $table->string('tp')->nullable();
            $table->string('nbanco')->nullable();
            $table->string('sucursal')->nullable();
            $table->string('tel')->nullable();
            $table->string('cel')->nullable();
            $table->string('correo')->nullable();
            $table->string('vpension')->nullable();
            $table->string('vsalud')->nullable();
            $table->string('vembargos')->nullable();
            $table->string('vdesc')->nullable();
            $table->string('cupo')->nullable();
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
        //
    }
}
