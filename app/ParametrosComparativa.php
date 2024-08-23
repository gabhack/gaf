<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParametrosComparativa extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'parametros_comparativa';

    protected $fillable = [
        'tipo',
        'masculino',
        'femenino',
        'edad_masculino',
        'edad_femenino',
        'tipo_contrato_masculino',
        'tipo_contrato_femenino',
        'cargo_masculino',
        'cargo_femenino',
        'horas_extras_masculino',
        'asignacion_aa_masculino',
        'asignacion_aaa_masculino',
        'horas_extras_femenino',
        'asignacion_aa_femenino',
        'asignacion_aaa_femenino',
        'codigo_cupon',
        'porcentaje_masculino',
        'porcentaje_femenino'
    ];
}
