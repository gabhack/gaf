<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalisisCarteraAvanzadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analisis_cartera_avanzado', function (Blueprint $table) {
            $table->increments('id');

            // Usuario que creó el estudio
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Políticas utilizadas
            $table->integer('politica_portafolio_id')->unsigned()->nullable()->index();
            $table->foreign('politica_portafolio_id')->references('id')->on('politicas_portafolio')->onDelete('set null');

            $table->integer('politica_fondo_id')->unsigned()->nullable()->index();
            $table->foreign('politica_fondo_id')->references('id')->on('politicas_fondos')->onDelete('set null');

            // Información del archivo cargado
            $table->string('nombre_archivo', 255)->comment('Nombre del archivo Excel cargado');

            // Periodo del análisis
            $table->string('mes', 2)->comment('Mes del análisis (MM)');
            $table->string('anio', 4)->comment('Año del análisis (YYYY)');
            $table->index(['mes', 'anio']);

            // Descripción opcional del estudio
            $table->string('descripcion', 500)->nullable()->comment('Descripción opcional del estudio');

            // Estadísticas generales
            $table->integer('total_registros')->default(0)->comment('Total de cédulas procesadas');
            $table->integer('registros_exitosos')->default(0)->comment('Registros procesados exitosamente');
            $table->integer('registros_con_errores')->default(0)->comment('Registros con errores o sin data');

            // Datos completos del análisis (JSON)
            $table->longText('datos_procesados')->comment('Array JSON con todos los resultados calculados');

            // Metadatos adicionales (JSON)
            $table->text('metadatos')->nullable()->comment('Información adicional: nombres de políticas, configuraciones, etc.');

            // Timestamps y soft deletes
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
        Schema::dropIfExists('analisis_cartera_avanzado');
    }
}
