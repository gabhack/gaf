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
            $table->text('nomp')->nullable();
            $table->text('docdeman')->nullable();
            $table->text('entidaddeman')->nullable();
            $table->text('fembini')->nullable();
            $table->text('fembfin')->nullable();
            $table->text('motemb')->nullable();
            $table->text('tingr')->nullable();
            $table->text('tegre')->nullable();
            $table->text('temb')->nullable();
            $table->text('netoemb')->nullable();
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
        Schema::dropIfExists('embargosseccali');
    }
}
