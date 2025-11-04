<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsToPoliticasFondosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('politicas_fondos', function (Blueprint $table) {
            $table->decimal('costo_asegurabilidad_mes', 20, 10)->default(0)->comment('Costo de Asegurabilidad Mes (%)')->after('tasa_usura');
            $table->decimal('descuento_max_saldo_total', 20, 10)->default(0)->comment('% Descuento Máximo Sobre Saldo Total')->after('costo_asegurabilidad_mes');
            $table->decimal('descuento_max_saldo_capital', 20, 10)->default(0)->comment('% Descuento Máximo Sobre Saldo Capital')->after('descuento_max_saldo_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('politicas_fondos', function (Blueprint $table) {
            $table->dropColumn(['costo_asegurabilidad_mes', 'descuento_max_saldo_total', 'descuento_max_saldo_capital']);
        });
    }
}
