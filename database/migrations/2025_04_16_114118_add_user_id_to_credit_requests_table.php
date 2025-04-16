<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToCreditRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('credit_requests', function (Blueprint $table) {
            // Aquí NO creamos foreign key
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('credit_requests', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
