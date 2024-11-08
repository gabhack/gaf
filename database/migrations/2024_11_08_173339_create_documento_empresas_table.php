<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentoEmpresasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('documento_empresas', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('empresa_id');
			$table->boolean('iva')->default(0);
			$table->boolean('contribuyente')->default(0);
			$table->boolean('autoretenedor')->default(0);
			$table->string('src_representante_legal');
			$table->string('src_camara_comercio');
			$table->string('src_rut');
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('empresa_id')->references('id')->on('empresas');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('documento_empresas');
	}
}
