<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSabanaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('sabana', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('doc');
            $table->text('nomfopep');
            $table->text('tfopep');
            $table->text('celfopep');
            $table->text('corfopep');
            $table->text('nfidu');
            $table->text('telfidu');
            $table->text('corfidu');
            $table->text('nsecvalle');
            $table->text('tsecvalle');
            $table->text('csecvalle');
            $table->text('nseccali');
            $table->text('tseccali');
            $table->text('cseccali');
            $table->text('fvincfopep');
            $table->text('departfopep');
            $table->text('municfopep');
            $table->text('tpfopep');
            $table->text('bfopep');
            $table->text('pagfopep');
            $table->text('ingfopep');
            $table->text('edadfopep');
            $table->text('municfidu');
            $table->text('generofidu');
            $table->text('estcivilfidu');
            $table->text('edadfidu');
            $table->text('descvincfidu');
            $table->text('tdescfidu');
            $table->text('depfidu');
            $table->text('fvincfidu');
            $table->text('tpfidu');
            $table->text('ingrfidu');
            $table->text('pagfidu');
            $table->text('antsecvalle');
            $table->text('edadsecvalle');
            $table->text('cargsecvalle');
            $table->text('tvincsecvalle');
            $table->text('estlabsecvalle');
            $table->text('municsecvalle');
            $table->text('ingsecvalle');
            $table->text('pagsecvalle');
            $table->text('edadseccali');
            $table->text('cargoseccali');
            $table->text('ncontrseccali');
            $table->text('slabseccali');
            $table->text('nvincseccali');
            $table->text('generoseccali');
            $table->text('cargoseccali');
            $table->text('ingrseccali');
            $table->text('pagseccali');
            $table->text('coincidenciaspag');
            $table->text('descaplfopep');
            $table->text('desnoapfopep');
            $table->text('alertliqseccali');
            $table->text('embseccali');
            $table->text('deduccseccali');
            $table->text('embsecvalle');
            $table->text('alerliqsecvalle');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sabana');
    }
}
