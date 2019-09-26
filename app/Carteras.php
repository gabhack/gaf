<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carteras extends Model
{
    use SoftDeletes;
    
	protected $table = 'carteras';
	
	public function estudio()
	{
		return $this->hasOne('\App\Estudiostr', 'id', 'estudios_id');
	}

	public function sector()
	{
		return $this->hasOne('\App\Sectores', 'id', 'sectores_id');
	}
	
	public function entidad()
	{
		return $this->hasOne('\App\Entidades', 'id', 'entidades_id');
	}
	
	public function estado()
	{
		return $this->hasOne('\App\Estadoscartera', 'id', 'estadoscarteras_id');
	}
}
