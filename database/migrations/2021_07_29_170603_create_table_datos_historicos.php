<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDatoshistoricos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datoshistoricos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('clientes_id')->index()->references('id')->on('clientes');
            $table->char('dato', 100);
            $table->char('valor', 255);
            //
            $table->foreign('clientes_id')->references('id')->on('clientes')->onDelete('cascade');
            //
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('datoshistoricos');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
