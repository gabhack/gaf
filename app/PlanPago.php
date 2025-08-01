<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanPago extends Model
{
    protected $table = "plan_pagos";

    protected $fillable = [
        'fecha',
        'cuota',
        'capital',
        'interes',
        'seguro_vida',
        'total_cuota',
        'saldo_capital',
        'estudio_id',
        'num_cuota'
    ];
}
