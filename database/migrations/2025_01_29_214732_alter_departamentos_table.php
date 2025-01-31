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
        Schema::table('departamentos', function (Blueprint $table) {
            $table->integer('pais_id')->unsigned()->after('id');
            $table->foreign('pais_id')->references('id')->on('paises');
            $table->string('codigo', 5)->nullable()->change();
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
            $table->dropForeign('departamentos_pais_id_foreign');
            $table->dropColumn('pais_id');
            $table->renameColumn('nombre', 'departamento');
        });

        DB::statement('ALTER TABLE departamentos MODIFY codigo INT NULL');
    }
}
