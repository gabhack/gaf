<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmbargosseceduTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('embargossecedu', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('doc');
            $table->text('nomp');
            $table->text('nitdeman');
            $table->text('ndeman');
            $table->text('finiemb');
            $table->text('ffinemb');
            $table->text('memb');
            $table->text('tingr');
            $table->text('tegre');
            $table->text('temb');
            $table->text('neto');
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
        Schema::dropIfExists('embargossecedu');
    }
}
