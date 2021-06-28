<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTiposClientesAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tiposcliente', function (Blueprint $table) {
            $table->dropColumn(['costoservicios']);
            $table->decimal('range_max', 11, 3)->after('tipo');
            $table->decimal('range_min', 11, 3)->after('tipo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tiposcliente', function (Blueprint $table) {
            $table->dropColumn(['range_min', 'range_max']);
            $table->decimal('costoservicios', 11, 3)->after('tipo');
        });
    }
}
