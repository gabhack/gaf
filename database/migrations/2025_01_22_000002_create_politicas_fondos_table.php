<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoliticasFondosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('politicas_fondos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_fondo', 250)->comment('Nombre descriptivo del fondo');
            $table->text('descripcion')->nullable()->comment('Descripción opcional del fondo');

            // Campos fijos (ingresados por el usuario)
            $table->decimal('smlv', 20, 2)->default(0)->comment('Salario Mínimo Legal Vigente');
            $table->integer('dias_mora_max')->default(0)->comment('Días de mora máximos permitidos');
            $table->integer('plazo_max')->default(0)->comment('Plazo máximo en días');
            $table->decimal('ta_min_ea', 20, 10)->default(0)->comment('Tasa Anual Mínima Efectiva Anual');
            $table->decimal('t_usura_ea', 20, 10)->default(0)->comment('Tasa de Usura Efectiva Anual');
            $table->decimal('tasa_usura', 20, 10)->default(0)->comment('Tasa de Usura');

            // Campos calculados automáticamente
            $table->decimal('saldo_max', 20, 2)->default(0)->comment('SALDO MAX = SMLV × 90');
            $table->decimal('ta_min_em', 20, 10)->default(0)->comment('T.A MIN (EM) = ((1+T.A MIN (EA))^(1/12))-1');
            $table->decimal('t_usura_menos2_ea', 20, 10)->default(0)->comment('T. USURA -2 (EA) = T. USURA (EA) - 2');
            $table->decimal('t_usura_em', 20, 10)->default(0)->comment('T. USURA (EM) = ((1+T. USURA (EA))^(1/12))-1');
            $table->decimal('t_usura_menos2_em', 20, 10)->default(0)->comment('T. USURA -2 (EM) = T. USURA -2 (EA) / 12');
            $table->decimal('t_usura_dia', 20, 10)->default(0)->comment('T. USURA (DIA) = T. USURA (EA) / 365');

            // Estado
            $table->boolean('activo')->default(true)->comment('Si el fondo está activo o inactivo');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('politicas_fondos');
    }
}
