<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterCiudadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ciudades', function (Blueprint $table) {
            $table->renameColumn('departamentos_id', 'departamento_id');
        });

        Schema::table('ciudades', function (Blueprint $table) {
            $table->unsignedBigInteger('departamento_id')->change();
            $table->foreign('departamento_id')->references('id')->on('departamentos');

            $table->renameColumn('ciudad', 'nombre');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ciudades', function (Blueprint $table) {
            $table->dropForeign(['departamento_id']);
            $table->dropIndex('ciudades_departamento_id_foreign');

            $table->renameColumn('departamento_id', 'departamentos_id');
        });

        Schema::table('ciudades', function (Blueprint $table) {
            $table->integer('departamentos_id')->change();
            $table->renameColumn('nombre', 'ciudad');
        });
    }
}
