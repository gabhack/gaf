<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('visados', function (Blueprint $table) {
            $table->increments('id');
            $table->string('conc');
            $table->string('estado');
            $table->string('fconsultaami');
            $table->string('ced');
            $table->string('nombre');
            $table->string('pagaduria');
            $table->string('tcredito');
            $table->string('clibinv');
            $table->string('ccompra');
            $table->string('entidad');
            $table->string('pagare');
            $table->string('vcredito');
            $table->string('vdesembolso');
            $table->string('plazo');
            $table->string('cuotacredito');
            $table->string('aprobado');
            $table->string('porcincorp');
            $table->string('cmaxincorp');
            $table->string('frespuesta');
            $table->string('fvinculacion');
            $table->string('tvinculacion');
            $table->string('tipo_consulta');
            $table->string('info_obligaciones');
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
        Schema::dropIfExists('visados');
    }
}
