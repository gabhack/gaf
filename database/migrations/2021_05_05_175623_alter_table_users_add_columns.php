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
            $table->dropForeign('fk_oficinas_usuarios');
            $table->dropColumn('oficinas_id');
            //
            $table->integer('id_padre', 11)->unsigned()->nullable()->after('roles_id');
            $table->integer('id_company', 11)->unsigned()->nullable()->after('roles_id');
            $table->integer('privilegios_ami', 1)->nullable()->after('roles_id');
            $table->integer('privilegios_hego', 1)->nullable()->after('roles_id');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_id_padre_foreign');
            $table->dropForeign('users_id_company_foreign');
            $table->dropColumn(['id_padre', 'id_company', 'privilegios_ami', 'privilegios_hego']);
        });
    }
}
