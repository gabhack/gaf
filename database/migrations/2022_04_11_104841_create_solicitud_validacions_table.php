<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudValidacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_validacions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('GuidConv')->nullable();
            $table->string('TipoValidacion')->nullable();
            $table->string('Asesor')->nullable();
            $table->string('Sede')->nullable();
            $table->string('TipoDoc')->nullable();
            $table->string('NumDoc')->nullable();
            $table->string('Email');
            $table->string('Celular')->nullable();
            $table->string('PrefCelular')->nullable();
            $table->string('ProcesoConvenioGuid')->nullable();
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
        Schema::dropIfExists('solicitud_validacions');
    }
}
