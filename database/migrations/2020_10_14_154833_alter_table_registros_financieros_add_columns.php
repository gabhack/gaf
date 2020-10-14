<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRegistrosFinancierosAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registros_financieros', function (Blueprint $table) {
            $table->integer('dias_laborados')->nullable()->unsigned()->after('periodo');
            $table->integer('ingresos_totales')->nullable()->unsigned()->after('periodo');
            $table->integer('egresos_totales')->nullable()->unsigned()->after('periodo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registros_financieros', function (Blueprint $table) {
            $table->dropColumn('dias_laborados', 'ingresos_totales', 'egresos_totales');
        });
    }
}
