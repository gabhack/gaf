<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_pagos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->decimal('cuota', 11, 2);
            $table->decimal('capital', 11, 2);
            $table->decimal('interes', 11, 2);
            $table->decimal('seguro_vida', 11, 2)->nullable();
            $table->decimal('total_cuota', 11, 2);
            $table->decimal('saldo_capital', 11, 2);
            $table->foreignId('estudio_id')->constrained()->onDelete('cascade');
            $table->integer('num_cuota');
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
        Schema::dropIfExists('plan_pagos');
    }
}
