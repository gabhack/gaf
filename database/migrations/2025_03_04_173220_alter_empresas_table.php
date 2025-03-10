<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('empresas', function (Blueprint $table) {
            $table->dropForeign(['ciudad_id']);
            $table->dropForeign(['departamento_id']);
            $table->dropForeign(['pais_id']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('empresas', function (Blueprint $table) {
            $table->foreign('ciudad_id')->references('id')->on('ciudades');
            $table->foreign('departamento_id')->references('id')->on('departamentos');
            $table->foreign('pais_id')->references('id')->on('paises');
        });

        Schema::enableForeignKeyConstraints();
    }
}
