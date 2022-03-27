<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Descnoap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::connection('pgsql')->create('descnoap', function($table){
            $table->increments('id');
            $table->string('clase');
            $table->string('tercero');
            $table->string('nomtercero');
            $table->string('td');
            $table->string('doc');
            $table->string('nomp');
            $table->string('pagare');
            $table->string('porcentaje');
            $table->string('vfijo');
            $table->string('vaplicado');
            $table->string('vtotal');
            $table->string('vpagado');
            $table->string('saldo');
            $table->string('fgrab');
            $table->string('forma');
            $table->string('incon');
            $table->string('codentiant');
            $table->string('nonentant');
            $table->string('fechacesion');
            $table->string('tdesc');
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
