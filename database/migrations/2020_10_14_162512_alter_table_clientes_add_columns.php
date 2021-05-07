<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableClientesAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->char('banco', 100)->nullable()->after('cargo');
            $table->char('cuenta', 100)->nullable()->after('cargo');
            $table->char('caja_compensacion', 100)->nullable()->after('cargo');
            $table->char('cesantias', 100)->nullable()->after('cargo');
            $table->char('pension', 100)->nullable()->after('cargo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('docente', 'cuenta', 'caja_compensacion', 'cesantias', 'pension');
        });
    }
}
