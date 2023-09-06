<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estudiostr extends Model
{
	use SoftDeletes;

	protected $table = 'estudiostr';

	protected $fillable = [
		'fecha', 'decision', 'user_id', 'data_cotizer_id'
	];


	public function cliente()
	{
		return $this->hasOne('\App\Clientes', 'id', 'clientes_id');
	}

	public function capacidad()
	{
		return $this->hasOne('\App\Capacidades', 'estudios_id', 'id');
	}

	public function adicional()
	{
		return $this->hasOne('\App\Clientesadicionales', 'estudios_id', 'id');
	}

	public function central()
	{
		return $this->hasOne('\App\Centrales', 'estudios_id', 'id');
	}

	public function condicion()
	{
		return $this->hasOne('\App\Condicionestr', 'estudios_id', 'id');
	}

	public function condicionck()
	{
		return $this->hasOne('\App\Condicionesck', 'estudios_id', 'id');
	}

	public function condicionesaf()
	{
		return $this->hasMany('\App\Condicionesaf', 'estudios_id', 'id');
	}

	public function asesor()
	{
		return $this->hasOne('\App\Asesores', 'id', 'asesores_id');
	}

	public function registro()
	{
		return $this->hasOne('\App\Registrosfinancieros', 'id', 'registros_id');
	}

	public function carteras()
	{
		return $this->hasMany('\App\Carteras', 'estudios_id', 'id');
	}
}
