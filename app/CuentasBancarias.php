<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuentasBancarias extends Model
{
    use SoftDeletes;
    
	protected $table = 'cuentas_bancarias';
	
	public function entidad()
	{
		return $this->hasOne('\App\Entidades', 'id', 'id_entidad');
	}
	
}
