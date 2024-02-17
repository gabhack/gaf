<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudCreditoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_credito', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor_solicitado', 11, 2);
            $table->integer('nro_cuotas');
            $table->decimal('aval', 11, 2)->nullable();
            $table->decimal('iva_aval', 11, 2)->nullable();
            $table->decimal('comision', 11, 2)->nullable();
            $table->decimal('valor1', 11, 2)->nullable();
            $table->decimal('valor2', 11, 2)->nullable();
            $table->decimal('iva_ck', 11, 2)->nullable();
            $table->decimal('interes_inicial', 11, 2)->nullable();
            $table->decimal('gmf', 11, 2)->nullable();
            $table->decimal('seguro', 11, 2)->nullable();
            $table->decimal('total_pagar', 11, 2);
            $table->decimal('cuota', 11, 2);
            $table->decimal('credito_total', 11, 2);
            $table->foreignId('estudio_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('tasa_interes', 5, 2)->nullable();
            $table->decimal('cuota_corriente', 11, 2)->nullable();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitud_credito');
    }
}
