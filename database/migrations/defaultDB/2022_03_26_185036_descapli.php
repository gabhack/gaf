<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Descapli extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('pgsql')->create('descalpi', function($table){
            $table->increments('id');
            $table->string('periodo');
            $table->string('concecutivo');
            $table->string('clase');
            $table->string('tercero');
            $table->string('nomtercero');
            $table->string('td');
            $table->string('doc');
            $table->string('nomp');
            $table->string('pagare');
            $table->string('porcentaje');
            $table->string('vaplicado');
            $table->string('vtotal');
            $table->string('vpagado');
            $table->string('saldo');
            $table->string('fgrab');
            $table->string('forma');
            $table->string('codentiant');
            $table->string('nonentant');
            $table->string('fechacesion');
            $table->string('tdesc');
            $table->string('p5d');
            $table->string('p4d');
            $table->string('numpagopt');
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
