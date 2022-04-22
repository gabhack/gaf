<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatamesfiduTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('datamesfidu', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('mnpio');
            $table->text('doc');
            $table->text('td');
            $table->text('tdd');
            $table->text('solonomp');
            $table->text('soloapellp');
            $table->text('genero');
            $table->text('estcivil');
            $table->text('fecnacimient');
            $table->text('edad');
            $table->text('tipvinc');
            $table->text('desctipvinc');
            $table->text('fuenrecurso');
            $table->text('descfuenrecurso');
            $table->text('numdep');
            $table->text('dpto');
            $table->text('resol');
            $table->text('fechresol');
            $table->text('fechefect');
            $table->text('vpension');
            $table->text('estpens');
            $table->text('docbenef');            
            $table->text('nombenef');
            $table->text('tel');
            $table->text('dir');
            $table->text('correo');
            $table->text('nomcomprob');
            $table->text('periodo');
            $table->text('tipprest');
            $table->text('fechpago');
            $table->text('sucursal');
            $table->text('vdescbruto');
            $table->text('pagonetbruto');
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
        Schema::dropIfExists('datamesfidu');
    }
}
