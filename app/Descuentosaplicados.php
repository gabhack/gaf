<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Descuentosaplicados extends Model
{
    use SoftDeletes;
    
	protected $table = 'descuentosaplicados';
	
	public function cliente()
	{
		return $this->hasOne('\App\Clientes', 'id', 'clientes_id');
	}
	
	public function registro()
	{
		return $this->hasOne('\App\Registrosfinancieros', 'id', 'registros_id');
	}
	
	public function tercero()
	{
		return $this->hasOne('\App\Entidades', 'id', 'entidades_id');
	}
}
