<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoSociedad extends Model
{
	use SoftDeletes;

	protected $table = 'tipo_sociedades';

	protected $fillable = [
		'nombre'
	];
}
