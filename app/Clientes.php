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
							'estado_civil',
							'direccion',
							'centro_costo',
							'cargo',
							'tipo_contratacion',
							'grado',
							'celular',
							'telefono',
							'correo',
							'estado',
							'ingresos',
						];
	
	public function ciudad()
	{
		return $this->hasOne('\App\Ciudades', 'id', 'ciudades_id');
	}

	public function registrosfinancieros()
	{
		return $this->hasMany('App\Registrosfinancieros', 'clientes_id', 'id');
	}

	public function descuentosnoaplicados()
	{
		return $this->hasMany('App\Descuentosnoaplicados', 'clientes_id', 'id');
	}

	public function mensajesprecaucion()
	{
		return $this->hasMany('App\Mensajesprecaucion', 'clientes_id', 'id');
	}

	public function embargos()
	{
		return $this->hasMany('App\Embargos', 'clientes_id', 'id');
	}
}
