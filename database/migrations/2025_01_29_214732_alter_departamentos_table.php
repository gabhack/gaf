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
        Schema::disableForeignKeyConstraints();

        DB::table('departamentos')->truncate();

        Schema::table('departamentos', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement()->change();
            $table->unsignedInteger('pais_id')->nullable()->after('id');
            $table->foreign('pais_id')->references('id')->on('paises');
            $table->integer('codigo')->nullable()->change();
            $table->renameColumn('departamento', 'nombre');
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

        Schema::table('departamentos', function (Blueprint $table) {
            $table->integer('id')->change();
        });

        Schema::table('departamentos', function (Blueprint $table) {
            $table->dropForeign(['pais_id']);
            $table->dropColumn('pais_id');
            $table->integer('codigo')->nullable()->change();
            $table->renameColumn('nombre', 'departamento');
        });

        Schema::enableForeignKeyConstraints();
    }
}
