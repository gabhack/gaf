<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Aliadosvalidos extends Model
{
    // use SoftDeletes;
    
	protected $table = 'aliadosvalidos';
	
	public function pagaduria()
	{
		return $this->hasOne('\App\Pagadurias', 'id', 'pagadurias_id');
	}
	
	public function tipoembargo()
	{
		return $this->hasOne('\App\Tiposembargos', 'id', 'tiposembargos_id');
	}
	
	public function aliado()
	{
		return $this->hasOne('\App\Aliados', 'id', 'aliados_id');
	}
}
