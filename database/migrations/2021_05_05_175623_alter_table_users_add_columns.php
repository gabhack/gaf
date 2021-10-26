<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsersAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'oficinas_id')) {
                $table->dropColumn('oficinas_id');
            }
            //
            $table->integer('hego')->nullable()->after('roles_id');
            $table->integer('creausuarios')->nullable()->after('roles_id');
            $table->integer('id_padre')->unsigned()->nullable()->after('roles_id');
            $table->integer('id_company')->unsigned()->nullable()->after('roles_id');
            //
            $table->foreign('id_padre')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_company')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::enableForeignKeyConstraints();
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_id_padre_foreign');
            $table->dropForeign('users_id_company_foreign');
            if (Schema::hasColumn('users', 'id_padre')) {
                $table->dropColumn('id_padre');
            }
            if (Schema::hasColumn('users', 'id_company')) {
                $table->dropColumn('id_company');
            }
            if (Schema::hasColumn('users', 'creausuarios')) {
                $table->dropColumn('creausuarios');
            }
            if (Schema::hasColumn('users', 'hego')) {
                $table->dropColumn('hego');
            }
        });
    }
}
