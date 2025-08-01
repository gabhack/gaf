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
            $table->string('periodo')->nullable();
            $table->string('concecutivo')->nullable();
            $table->string('clase')->nullable();
            $table->string('tercero')->nullable();
            $table->string('nomtercero')->nullable();
            $table->string('td')->nullable();
            $table->string('doc');
            $table->string('nomp')->nullable();
            $table->string('pagare')->nullable();
            $table->string('porcentaje')->nullable();
            $table->string('vaplicado')->nullable();
            $table->string('vtotal')->nullable();
            $table->string('vpagado')->nullable();
            $table->string('saldo')->nullable();
            $table->string('fgrab')->nullable();
            $table->string('forma')->nullable();
            $table->string('codentiant')->nullable();
            $table->string('nonentant')->nullable();
            $table->string('fechacesion')->nullable();
            $table->string('tdesc')->nullable();
            $table->string('p5d')->nullable();
            $table->string('p4d')->nullable();
            $table->string('numpagopt')->nullable();
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
