<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFactoresXMillonGnb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factores_x_millon_gnb', function (Blueprint $table) {
            $table->increments('id');
            $table->char('pagaduria', 200)->nullable();
            $table->char('plazo', 200)->nullable();
            $table->char('normal', 200)->nullable();
            $table->char('saneamiento', 200)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factores_x_millon_gnb');
    }
}
