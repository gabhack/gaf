<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clientes extends Model
{
    use SoftDeletes;
    
	protected $table = 'clientes';
	
	protected $fillable = [	'ciudades_id', 
							'users_id', 
							'tipodocumento', 
							'documento', 
							'nombres',
							'apellidos',
							'fechanto',							
							'sexo',
							'telefono',
							'direccion',
							'correo',
						];
	
	public function ciudad()
	{
		return $this->hasOne('\App\Ciudades', 'id', 'ciudades_id');
	}

    public function descuentosaplicados()
    {
        return $this->hasMany('App\Descuentosaplicados');
    }

    public function descuentosnoaplicados()
    {
        return $this->hasMany('App\Descuentosnoaplicados');
    }
}
