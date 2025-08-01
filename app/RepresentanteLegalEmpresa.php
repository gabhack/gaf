<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepresentanteLegalEmpresa extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'empresa_id',
		'tipo_documento_id',
		'nombres_completos',
		'numero_documento',
		'nacionalidad',
		'correo',
		'numero_contacto',
	];
}
