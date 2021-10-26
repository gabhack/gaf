<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableConsultasAddRegistroFinancieroColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->integer('registros_financieros_id')->after('documento')->index()->references('id')->on('registros_financieros')->nullable();
            //
            $table->foreign('registros_financieros_id')->references('id')->on('registros_financieros')->onDelete('cascade');
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
        Schema::table('consultas', function (Blueprint $table) {
            $table->dropColumn('registros_financieros_id');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
