<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement()->change();
            $table->renameColumn('rol', 'name');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->string('guard_name')->after('name');
            $table->dropSoftDeletes();

            $table->unique(['name', 'guard_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->bigInteger('id')->change();
            $table->dropUnique(['name', 'guard_name']);
            $table->dropColumn('guard_name');
            $table->renameColumn('name', 'rol');
            $table->softDeletes();
        });
    }
}
