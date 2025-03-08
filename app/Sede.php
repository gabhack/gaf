<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sede extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'empresa_id',
		'departamento_id',
		'ciudad_id',
		'nombre',
	];

	public function empresa()
	{
		return $this->belongsTo(Empresa::class)->withDefault();
	}

	public function departamento()
	{
		return $this->belongsTo(Departamentos::class)->withDefault();
	}

	public function ciudad()
	{
		return $this->belongsTo(Ciudades::class)->withDefault();
	}
}
