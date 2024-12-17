<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReporteBiometria extends Model
{
    //public $timestamps = true;
    protected $connection = 'pgsql';

    protected $table = 'reporte_de_biometria';
}