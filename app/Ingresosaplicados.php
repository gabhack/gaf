<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingresosaplicados extends Model
{
    use SoftDeletes;
    
	protected $table = 'ingresos_aplicados';
	
	public function registro()
	{
		return $this->hasOne('\App\Registrosfinancieros', 'id', 'registros_id');
	}
}
