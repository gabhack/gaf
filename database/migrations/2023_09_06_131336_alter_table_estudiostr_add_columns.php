<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEstudiostrAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estudiostr', function (Blueprint $table) {
            $table->integer('clientes_id')->nullable()->change();
            $table->unsignedInteger('user_id')->nullable()->change();
            $table->unsignedBigInteger('data_cotizer_id')->nullable();
            $table->foreign('data_cotizer_id')->references('id')->on('data_cotizer')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estudiostr', function (Blueprint $table) {
            $table->integer('clientes_id')->change();
            $table->unsignedInteger('user_id')->change();
            $table->dropForeign(['data_cotizer_id']);
            $table->dropColumn('data_cotizer_id');
        });
    }
}
