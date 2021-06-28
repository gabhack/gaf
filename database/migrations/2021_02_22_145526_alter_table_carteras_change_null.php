<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCarterasChangeNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carteras', function (Blueprint $table) {
            $table->integer('cuota')->nullable()->change();
            $table->integer('saldo')->nullable()->change();
            $table->integer('valor_ini')->nullable()->change();
            $table->integer('dcto_logrado')->nullable()->change();
            $table->date('fecha_vence')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carteras', function (Blueprint $table) {
            $table->integer('cuota')->nullable(false)->change();
            $table->integer('saldo')->nullable(false)->change();
            $table->integer('valor_ini')->nullable(false)->change();
            $table->integer('dcto_logrado')->nullable(false)->change();
            $table->date('fecha_vence')->nullable(false)->change();
        });
    }
}
