<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoEmpresa extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'empresa_id',
		'iva',
		'contribuyente',
		'autoretenedor',
		'src_representante_legal',
		'src_camara_comercio',
		'src_rut',
	];
}
