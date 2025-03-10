<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPaisToEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Agregar 'pais_id' si no existe
        if (!Schema::hasColumn('empresas', 'pais_id')) {
            Schema::table('empresas', function (Blueprint $table) {
                $table->unsignedInteger('pais_id')->after('pagina_web');
            });
            Schema::table('empresas', function (Blueprint $table) {
                $table->foreign('pais_id')->references('id')->on('paises');
            });
        }

        // Agregar 'departamento_id' si no existe
        if (!Schema::hasColumn('empresas', 'departamento_id')) {
            Schema::table('empresas', function (Blueprint $table) {
                $table->unsignedBigInteger('departamento_id')->after('pais_id');
            });
            Schema::table('empresas', function (Blueprint $table) {
                $table->foreign('departamento_id')->references('id')->on('departamentos');
            });
        }

        // Eliminar la columna 'pais' si existe
        if (Schema::hasColumn('empresas', 'pais')) {
            Schema::table('empresas', function (Blueprint $table) {
                $table->dropColumn('pais');
            });
        }

        DB::statement('ALTER TABLE empresas MODIFY ciudad_id INTEGER NOT NULL AFTER departamento_id');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar 'pais_id' si existe
        if (Schema::hasColumn('empresas', 'pais_id')) {
            Schema::table('empresas', function (Blueprint $table) {
                $table->dropForeign(['pais_id']);
                $table->dropColumn('pais_id');
            });
        }

        // Eliminar 'departamento_id' si existe
        if (Schema::hasColumn('empresas', 'departamento_id')) {
            Schema::table('empresas', function (Blueprint $table) {
                $table->dropForeign(['departamento_id']);
                $table->dropColumn('departamento_id');
            });
        }

        // Volver a agregar la columna 'pais' si no existe
        if (!Schema::hasColumn('empresas', 'pais')) {
            Schema::table('empresas', function (Blueprint $table) {
                $table->string('pais')->after('pagina_web');
            });
        }

        DB::statement('ALTER TABLE empresas MODIFY ciudad_id INTEGER NOT NULL AFTER tipo_documento_id');
    }
}
