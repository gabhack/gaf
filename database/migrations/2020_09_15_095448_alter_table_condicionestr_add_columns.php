<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCondicionestrAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('condicionestr', function (Blueprint $table) {
            $table->decimal('costo_servicio', 11, 3)->after('costocertificados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('condicionestr', function (Blueprint $table) {
            $table->dropColumn(['costo_servicio']);
        });
    }
}
