<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatamesSemCaliTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('datamessemcali', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('nvinc')->nullable();
            $table->text('doc');
            $table->text('nomp')->nullable();
            $table->text('fingr')->nullable();
            $table->text('fretir')->nullable();
            $table->text('antigüedad')->nullable();
            $table->text('fecnacimient')->nullable();
            $table->text('edad')->nullable();
            $table->text('esquema')->nullable();
            $table->text('codcargo')->nullable();
            $table->text('cargo')->nullable();
            $table->text('grado')->nullable();
            $table->text('escalafon')->nullable();
            $table->text('vingreso')->nullable();
            $table->text('codencargo')->nullable();
            $table->text('gradoenc')->nullable();
            $table->text('coddep')->nullable();
            $table->text('fnombramiento')->nullable();
            $table->text('nnombramiento')->nullable();
            $table->text('fpose')->nullable();
            $table->text('npose')->nullable();
            $table->text('continuidad')->nullable();
            $table->text('fconti')->nullable();
            $table->text('cconti')->nullable();
            $table->text('mconti')->nullable();
            $table->text('frecur')->nullable();
            $table->text('ncontr')->nullable();
            $table->text('slabor')->nullable();
            $table->text('rvinc')->nullable();
            $table->text('novvinc')->nullable();
            $table->text('codcosto')->nullable();
            $table->text('cencosto')->nullable();
            $table->text('ciudad')->nullable();
            $table->text('genero')->nullable();
            $table->text('prof')->nullable();
            $table->text('tel')->nullable();
            $table->text('dir')->nullable();
            $table->text('email')->nullable();
            $table->text('tcargo')->nullable();
            $table->text('status')->nullable();
            $table->text('ncdb')->nullable();
            $table->text('ccpp')->nullable();
            $table->text('codareaedu')->nullable();
            $table->text('areaedu')->nullable();
            $table->text('codareaedutec')->nullable();
            $table->text('areaedutec')->nullable();
            $table->text('otraareaedutec')->nullable();
            $table->text('ndicta')->nullable();
            $table->text('tdepen')->nullable();
            $table->text('cdepen')->nullable();
            $table->text('depen')->nullable();
            $table->text('codesteduc')->nullable();
            $table->text('eeduc')->nullable();
            $table->text('deduc')->nullable();
            $table->text('ubicación')->nullable();
            $table->text('column1')->nullable();
            $table->text('sede2')->nullable();
            $table->text('eeduc2')->nullable();
            $table->text('fecdata')->nullable();
            $table->text('mesdata')->nullable();
            $table->text('anodata')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datamessemcali');
    }
}
