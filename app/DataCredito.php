<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DataCredito extends Authenticatable
{
    use Notifiable;

    protected $table = 'Datacredito';

    protected $fillable = [
        'id',
        'usuarioid',
        'idtransaccion',
        'nombre',
        'apellido',
        'cedula',
        'respuesta',
        'status',
    ];
}
