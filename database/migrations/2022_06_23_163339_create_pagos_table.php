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
            $table->integer('idtransaccion');
            $table->string('nombre', 250);
            $table->string('apellido', 200);
            $table->string('email', 50);
            $table->string('telefono', 15);
            $table->string('concepto', 200);
            $table->string('tipopago', 100);
            $table->double('monto');
            $table->string('tarjeta', 25);
            $table->string('mes', 2);
            $table->string('year', 2);
            $table->string('cvv', 5);
            $table->string('status', 250);
            $table->text('respuesta');
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
