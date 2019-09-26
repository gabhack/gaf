<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clientesadicionales extends Model
{
    use SoftDeletes;
    
	protected $table = 'clientesadicionales';
	
	public function estudio()
	{
		return $this->hasOne('\App\Estudiostr', 'id', 'estudios_id');
	}
	
	public function cargo()
	{
		return $this->hasOne('\App\Cargos', 'id', 'cargos_id');
	}
}
