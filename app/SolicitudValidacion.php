<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudValidacion extends Model
{
    protected $table = 'solicitud_validacions';
    protected $guarded = ['id'];
    protected $fillable = [
        'GuidConv',
        'TipoValidacion',
        'Asesor',
        'Sede',
        'TipoDoc',
        'NumDoc',
        'Email',
        'Celular',
        'PrefCelular ',
        'ProcesoConvenioGuid'
    ];
}
