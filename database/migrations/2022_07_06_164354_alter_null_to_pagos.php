<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNullToPagos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->string('idtransaccion')->nullable()->change();
            $table->string('nombre', 250)->nullable()->change();
            $table->string('apellido', 200)->nullable()->change();
            $table->string('email', 50)->nullable()->change();
            $table->string('telefono', 15)->nullable()->change();
            $table->string('concepto', 200)->nullable()->change();
            $table->string('tipopago', 100)->nullable()->change();
            $table->string('tarjeta', 25)->nullable()->change();
            $table->string('mes', 2)->nullable()->change();
            $table->string('year', 2)->nullable()->change();
            $table->string('cvv', 5)->nullable()->change();
            $table->text('respuesta')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
            //
        });
    }
}
