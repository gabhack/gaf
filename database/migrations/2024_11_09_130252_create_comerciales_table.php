<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComercialesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comerciales', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('sede_id');
			$table->integer('cargo_id');
			$table->unsignedInteger('tipo_documento_id');
			$table->unsignedInteger('ami_id');
			$table->unsignedInteger('hego_id');
			$table->integer('consultas_diarias');
			$table->string('nombre_completo');
			$table->bigInteger('numero_documento');
			$table->string('nacionalidad');
			$table->string('correo');
			$table->bigInteger('numero_contacto');
			$table->string('src_documento_identidad');
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('sede_id')->references('id')->on('sedes');
			$table->foreign('cargo_id')->references('id')->on('cargos');
			$table->foreign('tipo_documento_id')->references('id')->on('tipo_documentos');
			$table->foreign('ami_id')->references('id')->on('amis');
			$table->foreign('hego_id')->references('id')->on('hegos');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('comerciales');
	}
}
