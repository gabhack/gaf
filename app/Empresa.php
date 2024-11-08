<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'tipo_sociedad_id',
		'tipo_empresa_id',
		'tipo_documento_id',
		'ciudad_id',
		'consultas_diarias',
		'nombre',
		'numero_documento',
		'correo',
		'pagina_web',
		'pais',
		'direccion',
	];

	public function tipo_sociedad()
	{
		return $this->belongsTo(TipoSociedad::class)->withDefault();
	}

	public function tipo_empresa()
	{
		return $this->belongsTo(TipoEmpresa::class)->withDefault();
	}

	public function tipo_documento()
	{
		return $this->belongsTo(TipoDocumento::class)->withDefault();
	}

	public function ciudad()
	{
		return $this->belongsTo(Ciudades::class)->withDefault();
	}
}
