<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fiducidiaria extends Model
{

    protected $connection = 'pgsql';

    protected $table = 'fiducidiaria';

    protected $fillable = [
        'DOCUMENTO',
        'NOMBRES',
        'APELLIDOS',
        'SEXO',
        'ESTADO_CIVIL',
        'FECHA_NACIMIENTO_DOCENTE',
        'EDAD_ACTUAL',
        'ESTADO_PENSIONADO',
        'NOM_DEPTO',
        'VALOR_MESADA',
        'VALORBRUTO',
        'VALORDESCUENTOS',
        'PAGO_NET'
    ];
}
