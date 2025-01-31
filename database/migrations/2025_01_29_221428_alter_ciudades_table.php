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
            $table->dropForeign('fk_departamentos_ciudades');
            $table->renameColumn('departamentos_id', 'departamento_id');
            $table->foreign('departamento_id')->references('id')->on('departamentos');
            $table->string('codigo', 5)->nullable()->change();
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
            $table->dropForeign('ciudades_departamento_id_foreign');
            $table->renameColumn('departamento_id', 'departamentos_id');
            $table->foreign('departamentos_id', 'fk_departamentos_ciudades')->references('id')->on('departamentos');
            $table->renameColumn('nombre', 'ciudad');
        });

        DB::statement('ALTER TABLE ciudades MODIFY codigo INT NULL');
    }
}
