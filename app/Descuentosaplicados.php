<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Descuentosaplicados extends Model
{
    use SoftDeletes;
    
	protected $table = 'descuentosaplicados';
	
	public function registro()
	{
		return $this->hasOne('\App\Registrosfinancieros', 'id', 'registros_id');
	}
	
	public function tercero()
	{
		return $this->hasOne('\App\Entidades', 'id', 'entidades_id');
	}
}
