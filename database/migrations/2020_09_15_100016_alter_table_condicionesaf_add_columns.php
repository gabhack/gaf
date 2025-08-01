<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCondicionesafAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('condicionesaf', function (Blueprint $table) {
            $table->integer('saldo_refinanciacion', false)->nullable()->after('valor_titulos');
            $table->decimal('intereses_anticipados', 11, 3)->nullable()->after('valor_titulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('condicionesaf', function (Blueprint $table) {
            $table->dropColumn(['saldo_refinanciacion', 'intereses_anticipados']);
        });
    }
}
