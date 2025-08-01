<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmbargosSedValleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('embargossedvalle', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('doc');
            $table->text('nomp')->nullable();
            $table->text('nitdeman')->nullable();
            $table->text('ndeman')->nullable();
            $table->text('finiemb')->nullable();
            $table->text('ffinemb')->nullable();
            $table->text('memb')->nullable();
            $table->text('tingr')->nullable();
            $table->text('tegre')->nullable();
            $table->text('temb')->nullable();
            $table->text('neto')->nullable();
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
        Schema::dropIfExists('embargossedvalle');
    }
}
