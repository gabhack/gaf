<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatamesseceducTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('datamesseceduc', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('doc');
            $table->text('nomp');
            $table->text('fechingr');
            $table->text('antiguedad');
            $table->text('fecnacimient');
            $table->text('edad');
            $table->text('esquema');
            $table->text('cargo');
            $table->text('vpension');
            $table->text('fecnombr');
            $table->text('fecposesion');
            $table->text('nivcontr');
            $table->text('estlaboral');
            $table->text('centrocosto');
            $table->text('mnpioydep');
            $table->text('tel');
            $table->text('dir');
            $table->text('correo');
            $table->text('sedecoleg');
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
        Schema::dropIfExists('datamesseceduc');
    }
}
