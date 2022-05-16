<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DataCotizer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_cotizer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gender');
            $table->string('firstName');
            $table->string('middleName');
            $table->string('firstLastname');
            $table->string('secondLastname');
            $table->string('birthday');
            $table->string('idType');
            $table->string('idNumber');
            $table->string('idExpeditionDate');
            $table->string('maritalStatus');
            $table->string('childs');
            $table->string('living');
            $table->string('studies');
            $table->string('city');
            $table->string('time');
            $table->string('estrato');
            $table->string('phoneNumber');
            $table->string('phoneNumberFijo');
            $table->string('email')->unique();
            $table->string('transport');
            $table->string('others');
            $table->string('factoryName');
            $table->string('startDate');
            $table->string('workCity');
            $table->string('addressWork');
            $table->string('phoneWork');
            $table->string('nit');
            $table->string('ingreso');
            $table->string('gasto');
            $table->string('neto');
            $table->string('accountType');
            $table->string('accountNumber');
            $table->string('referenceName');
            $table->string('referencePhone');
            $table->string('referenceCity');
            $table->string('referenceAddress');
            $table->string('referenceBarrio');
            $table->string('referenceParent');
            $table->string('referenceFName');
            $table->string('referenceFPhone');
            $table->string('referenceFCity');
            $table->string('referenceFAddress');
            $table->string('referenceFBarrio');
            $table->string('referenceFParent');
            $table->string('referenceFState');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_cotizer');
    }
}
