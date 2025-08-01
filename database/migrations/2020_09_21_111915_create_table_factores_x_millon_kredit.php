<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFactoresXMillonKredit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factores_x_millon_kredit', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('llave', 200)->nullable();
            $table->char('valor', 200)->nullable();
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
        Schema::table('factores_x_millon_kredit', function (Blueprint $table) {
            Schema::dropIfExists('factores_x_millon_kredit');
        });
    }
}
