<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepresentanteLegalEmpresasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('representante_legal_empresas', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('empresa_id');
			$table->unsignedInteger('tipo_documento_id');
			$table->string('nombres_completos');
			$table->bigInteger('numero_documento');
			$table->string('nacionalidad');
			$table->string('correo');
			$table->bigInteger('numero_contacto');
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('empresa_id')->references('id')->on('empresas');
			$table->foreign('tipo_documento_id')->references('id')->on('tipo_documentos');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('representante_legal_empresas');
	}
}
