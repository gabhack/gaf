<?php

use App\Departamentos;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterDepartamentosTable extends Migration
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
            $table->dropIndex('fk_departamentos_ciudades');
        });

        Schema::table('departamentos', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement()->change();
            $table->unsignedInteger('pais_id')->nullable()->after('id');
            $table->foreign('pais_id')->references('id')->on('paises');
            $table->integer('codigo')->nullable()->change();
            $table->renameColumn('departamento', 'nombre');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('departamentos', function (Blueprint $table) {
            $table->integer('id')->change();
        });

        Schema::table('ciudades', function (Blueprint $table) {
            $table->foreign('departamentos_id', 'fk_departamentos_ciudades')
                ->references('id')->on('departamentos');
        });

        Schema::table('departamentos', function (Blueprint $table) {
            $table->dropForeign(['pais_id']);
            $table->dropColumn('pais_id');
            $table->integer('codigo')->nullable()->change();
            $table->renameColumn('nombre', 'departamento');
        });
    }
}
