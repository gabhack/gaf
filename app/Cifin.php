<?php

namespace App;
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Cifin extends Authenticatable
{
    use Notifiable;
    
	protected $table = 'cifin';

	protected $fillable = [	'id',
		'usuarioid',
		'idtransaccion',
		'nombre',
		'apellido',
		'cedula',
		'respuesta',
		'status',
	];
}
