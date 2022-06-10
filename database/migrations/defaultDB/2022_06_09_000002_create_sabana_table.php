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
            $table->text('nomfopep')->nullable();
            $table->text('tfopep')->nullable();
            $table->text('celfopep')->nullable();
            $table->text('corfopep')->nullable();
            $table->text('nfidu')->nullable();
            $table->text('telfidu')->nullable();
            $table->text('corfidu')->nullable();
            $table->text('nsecvalle')->nullable();
            $table->text('tsecvalle')->nullable();
            $table->text('csecvalle')->nullable();
            $table->text('nseccali')->nullable();
            $table->text('tseccali')->nullable();
            $table->text('cseccali')->nullable();
            $table->text('fvincfopep')->nullable();
            $table->text('departfopep')->nullable();
            $table->text('municfopep')->nullable();
            $table->text('tpfopep')->nullable();
            $table->text('bfopep')->nullable();
            $table->text('pagfopep')->nullable();
            $table->text('ingfopep')->nullable();
            $table->text('edadfopep')->nullable();
            $table->text('municfidu')->nullable();
            $table->text('generofidu')->nullable();
            $table->text('estcivilfidu')->nullable();
            $table->text('edadfidu')->nullable();
            $table->text('descvincfidu')->nullable();
            $table->text('tdescfidu')->nullable();
            $table->text('depfidu')->nullable();
            $table->text('fvincfidu')->nullable();
            $table->text('tpfidu')->nullable();
            $table->text('ingrfidu')->nullable();
            $table->text('pagfidu')->nullable();
            $table->text('antsecvalle')->nullable();
            $table->text('edadsecvalle')->nullable();
            $table->text('cargsecvalle')->nullable();
            $table->text('tvincsecvalle')->nullable();
            $table->text('estlabsecvalle')->nullable();
            $table->text('municsecvalle')->nullable();
            $table->text('ingsecvalle')->nullable();
            $table->text('pagsecvalle')->nullable();
            $table->text('edadseccali')->nullable();
            $table->text('cargoseccali')->nullable();
            $table->text('ncontrseccali')->nullable();
            $table->text('slabseccali')->nullable();
            $table->text('nvincseccali')->nullable();
            $table->text('generoseccali')->nullable();
            $table->text('cargoseccali')->nullable();
            $table->text('ingrseccali')->nullable();
            $table->text('pagseccali')->nullable();
            $table->text('coincidenciaspag')->nullable();
            $table->text('descaplfopep')->nullable();
            $table->text('desnoapfopep')->nullable();
            $table->text('alertliqseccali')->nullable();
            $table->text('embseccali')->nullable();
            $table->text('deduccseccali')->nullable();
            $table->text('embsecvalle')->nullable();
            $table->text('alerliqsecvalle')->nullable();


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
