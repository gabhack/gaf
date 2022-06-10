<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeduccionesseccaliTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('pgsql')->create('deduccionesseccali', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('doc');
            $table->text('nomp')->nullable();
            $table->text('valordeduc')->nullable();
            $table->text('centrocostdeduc')->nullable();
            $table->text('entiddeduc')->nullable();
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
        Schema::dropIfExists('deduccionesseccali');
    }
}
