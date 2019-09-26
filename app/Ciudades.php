<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ciudades extends Model
{
    use SoftDeletes;
    
	protected $table = 'ciudades';
	
	public function departamento()
	{
		return $this->hasOne('\App\Departamentos', 'id', 'departamentos_id');
	}
	
	public function oficinas()
	{
		return $this->hasMany('\App\Oficinas', 'ciudad_id');
	}
}
