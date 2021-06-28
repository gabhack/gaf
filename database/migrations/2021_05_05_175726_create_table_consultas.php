<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableConsultas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('users_id')->references('id')->on('users');
            $table->unsignedInteger('documento');
            $table->integer('tipo_consulta')->nullable();
            //
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::table('consultas', function (Blueprint $table) {
            $table->dropForeign('consultas_users_id_foreign');
        });
        Schema::dropIfExists('consultas');
    }
}
