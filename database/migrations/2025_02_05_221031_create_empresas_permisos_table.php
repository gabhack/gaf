<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasPermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas_permisos', function (Blueprint $table) {
            $table->unsignedBigInteger('permiso_id');
            $table->foreign('permiso_id')->references('id')->on('permisos')->onDelete('cascade');

            $table->unsignedInteger('empresa_id');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');

            $table->primary(['permiso_id', 'empresa_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas_permisos');
    }
}
