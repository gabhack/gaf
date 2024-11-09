<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSedesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sedes', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('ciudad_id');
			$table->string('nombre');
			$table->timestamps();
			$table->softDeletes();

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
		Schema::dropIfExists('sedes');
	}
}
