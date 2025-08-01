<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mensajesprecaucion extends Model
{
    use SoftDeletes;
    
	protected $table = 'mensajes_precaucion';
	
	public function cliente()
	{
		return $this->hasOne('\App\Clientes', 'id', 'clientes_id');
	}
}
