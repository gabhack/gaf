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
        Schema::table('empresas', function (Blueprint $table) {
            $table->unsignedInteger('pais_id')->after('pagina_web');
            $table->foreign('pais_id')->references('id')->on('paises');

            $table->integer('departamento_id')->unsigned()->after('pais_id');
            $table->foreign('departamento_id')->references('id')->on('departamentos');

            // $table->unsignedInteger('ciudad_id')->change();
            $table->dropColumn('pais');
        });

        DB::statement('ALTER TABLE empresas MODIFY ciudad_id INTEGER NOT NULL AFTER departamento_id');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropForeign(['pais_id']);
            $table->dropColumn('pais_id');

            $table->dropForeign(['departamento_id']);
            $table->dropColumn('departamento_id');

            $table->string('pais')->after('pagina_web');
        });

        DB::statement('ALTER TABLE empresas MODIFY ciudad_id INTEGER NOT NULL AFTER tipo_documento_id');
    }
}
