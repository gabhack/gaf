<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEmbargosAlterColumnFechaembargo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('embargos', function (Blueprint $table) {
            $table->date('fecha_embargo')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('embargos', function (Blueprint $table) {
            $table->date('fecha_embargo')->nullable(false)->change();
        });
    }
}
