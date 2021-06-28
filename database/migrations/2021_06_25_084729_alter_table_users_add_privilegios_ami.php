<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsersAddPrivilegiosAmi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('ami_silver')->nullable()->after('roles_id');
            $table->integer('ami_gold')->nullable()->after('roles_id');
            $table->integer('ami_diamond')->nullable()->after('roles_id');
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
            if (Schema::hasColumn('users', 'ami_silver')) {
                $table->dropColumn('ami_silver');
            }
            if (Schema::hasColumn('users', 'ami_gold')) {
                $table->dropColumn('ami_gold');
            }
            if (Schema::hasColumn('users', 'ami_diamond')) {
                $table->dropColumn('ami_diamond');
            }
        });
    }
}
