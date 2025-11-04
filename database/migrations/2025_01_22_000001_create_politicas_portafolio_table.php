<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoliticasPortafolioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('politicas_portafolio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 250)->comment('Nombre descriptivo de la política');
            $table->text('descripcion')->nullable()->comment('Descripción opcional de la política');

            // Porcentajes
            $table->decimal('porcentaje_compra_portafolio', 20, 10)->default(0)->comment('% de compra del portafolio');
            $table->decimal('porcentaje_comision_comercial', 20, 10)->default(0)->comment('% de comisión comercial');
            $table->decimal('porcentaje_reincorporacion_gaf', 20, 10)->default(0)->comment('% de re-incorporación GAF');
            $table->decimal('costo_administracion', 20, 10)->default(0)->comment('% de costo de administración');
            $table->decimal('porcentaje_costo_seguro_vd', 20, 10)->default(0)->comment('% de costo de seguro V.D');

            // Valores monetarios
            $table->decimal('costo_reporte_centrales', 15, 2)->default(0)->comment('Costo monetario del reporte de centrales');
            $table->decimal('tecnologia', 15, 2)->default(0)->comment('Costo monetario de tecnología');

            // Estado
            $table->boolean('activo')->default(true)->comment('Si la política está activa o inactiva');

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
        Schema::dropIfExists('politicas_portafolio');
    }
}
