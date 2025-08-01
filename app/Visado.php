<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visado extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'visados';

    protected $guarded = ['id'];

    protected $fillable = [
        'conc',
        'estado',
        'causal',
        'fconsultaami',
        'ced',
        'nombre',
        'pagaduria',
        'tcredito',
        'clibinv',
        'ccompra',
        'entidad',
        'pagare',
        'vcredito',
        'vdesembolso',
        'plazo',
        'cuotacredito',
        'monto',
        'aprobado',
        'porcincorp',
        'cmaxincorp',
        'frespuesta',
        'fvinculacion',
        'tvinculacion',
        'tipo_consulta',
        'info_obligaciones',
        'consultant_email',
        'consultant_name',
        'observacion'
    ];
}
