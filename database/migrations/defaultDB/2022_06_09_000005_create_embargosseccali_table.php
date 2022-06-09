<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmbargosseccaliTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('embargosseccali', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('doc');
            $table->text('nomp');
            $table->text('docdeman');
            $table->text('entidaddeman');
            $table->text('fembini');
            $table->text('fembfin');
            $table->text('motemb');
            $table->text('tingr');
            $table->text('tegre');
            $table->text('temb');
            $table->text('netoemb');
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
        Schema::dropIfExists('embargosseccali');
    }
}
