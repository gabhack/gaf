<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatamesseccaliTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('datamesseccali', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('nvinc');
            $table->text('doc');
            $table->text('nomp');
            $table->text('fingr');
            $table->text('fretir');
            $table->text('antigüedad');
            $table->text('fecnacimient');
            $table->text('edad');
            $table->text('esquema');
            $table->text('codcargo');
            $table->text('cargo');
            $table->text('grado');
            $table->text('escalafon');
            $table->text('vingreso');
            $table->text('codencargo');
            $table->text('gradoenc');
            $table->text('coddep');
            $table->text('fnombramiento');
            $table->text('nnombramiento');
            $table->text('fpose');
            $table->text('npose');
            $table->text('continuidad');
            $table->text('fconti');
            $table->text('cconti');
            $table->text('mconti');
            $table->text('frecur');
            $table->text('ncontr');
            $table->text('slabor');
            $table->text('rvinc');
            $table->text('novvinc');
            $table->text('codcosto');
            $table->text('cencosto');
            $table->text('ciudad');
            $table->text('genero');
            $table->text('prof');
            $table->text('tel');
            $table->text('dir');
            $table->text('email');
            $table->text('tcargo');
            $table->text('status');
            $table->text('ncdb');
            $table->text('ccpp');
            $table->text('codareaedu');
            $table->text('areaedu');
            $table->text('codareaedutec');
            $table->text('areaedutec');
            $table->text('otraareaedutec');
            $table->text('ndicta');
            $table->text('tdepen');
            $table->text('cdepen');
            $table->text('depen');
            $table->text('codesteduc');
            $table->text('eeduc');
            $table->text('deduc');
            $table->text('ubicación');
            $table->text('column1');
            $table->text('sede2');
            $table->text('eeduc2');
            $table->text('fecdata');
            $table->text('mesdata');
            $table->text('anodata');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datamesseccali');
    }
}
