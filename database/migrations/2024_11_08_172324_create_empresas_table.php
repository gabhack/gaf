<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('empresas', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('tipo_sociedad_id');
			$table->unsignedInteger('tipo_empresa_id');
			$table->unsignedInteger('tipo_documento_id');
			$table->integer('ciudad_id');
			$table->string('consultas_diarias');
			$table->string('nombre');
			$table->string('numero_documento');
			$table->string('correo');
			$table->string('pagina_web');
			$table->string('pais');
			$table->string('direccion');
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('tipo_sociedad_id')->references('id')->on('tipo_sociedades');
			$table->foreign('tipo_empresa_id')->references('id')->on('tipo_empresas');
			$table->foreign('tipo_documento_id')->references('id')->on('tipo_documentos');
			$table->foreign('ciudad_id')->references('id')->on('ciudades');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('empresas');
	}
}
