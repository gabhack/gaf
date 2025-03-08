<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSedesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('sedes', function (Blueprint $table) {
            $table->dropForeign(['ciudad_id']);
            $table->unsignedInteger('empresa_id')->after('id');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->unsignedBigInteger('departamento_id')->after('empresa_id');
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

        Schema::table('sedes', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropColumn('empresa_id');
            $table->dropColumn('departamento_id');
            $table->foreign('ciudad_id')->references('id')->on('ciudades');
        });

        Schema::enableForeignKeyConstraints();
    }
}
