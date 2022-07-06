<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('usuarioid');
            $table->integer('idtransaccion')->nullable();
            $table->string('nombre', 250)->nullable();
            $table->string('apellido', 200)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('telefono', 15)->nullable();
            $table->string('concepto', 200)->nullable();
            $table->string('tipopago', 100)->nullable();
            $table->double('monto')->nullable();
            $table->string('tarjeta', 25)->nullable();
            $table->string('mes', 2)->nullable();
            $table->string('year', 2)->nullable();
            $table->string('cvv', 5)->nullable();
            $table->string('status', 250);
            $table->text('respuesta')->nullable();
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
        Schema::dropIfExists('pagos');
    }
}
