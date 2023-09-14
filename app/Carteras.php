<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carteras extends Model
{
    use SoftDeletes;
    
	protected $table = 'carteras';

	protected $fillable = [
		'sector_data', 'sector_cifin', 'estadoscarteras_id', 'nombre_obligacion', 'estudios_id', 'cuota', 'saldo', 'valor_ini', 'fecha_vence'
	];

	
	public function estudio()
	{
		return $this->hasOne('\App\Estudiostr', 'id', 'estudios_id');
	}

	public function sectordata()
	{
		return $this->hasOne('\App\Sectores', 'id', 'sector_data');
	}

	public function sectorcifin()
	{
		return $this->hasOne('\App\Sectores', 'id', 'sector_cifin');
	}
	
	public function entidad()
	{
		return $this->hasOne('\App\Entidades', 'id', 'entidades_id');
	}
	
	public function estado()
	{
		return $this->hasOne('\App\Estadoscartera', 'id', 'estadoscarteras_id');
	}
	
	public function aliado()
	{
		return $this->hasOne('\App\Aliados', 'id', 'aliadocompra_id');
	}
}
