<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargaArchivoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carga_archivo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('tipo', 30)->nullable();
            $table->binary('entidades')->nullable();
            $table->char('nombre_archivo', 200)->nullable();
            $table->bigInteger('cont_procesos')->default(0);
            $table->longText('errors')->nullable();
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
        Schema::dropIfExists('carga_archivo');
    }
}
