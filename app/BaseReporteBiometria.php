<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseReporteBiometria extends Model
{
    //public $timestamps = true;
    protected $connection = 'pgsql';

    protected $table = 'base_reporte_biometria';
}