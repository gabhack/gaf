<?php

namespace App;
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pagos extends Authenticatable
{
    use Notifiable;
    
	protected $table = 'pagos';

	protected $fillable = [	'id',
		'usuarioid',
		'idtransaccion',
		'nombre',
		'apellido',
		'email',
		'telefono',
		'concepto',
		'tipopago',
		'monto',
		'tarjeta',
		'mes',
		'year',
		'cvv',
		'status',
		'respuesta',
	];
}
