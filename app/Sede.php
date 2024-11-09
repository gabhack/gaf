<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sede extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'ciudad_id',
		'nombre',
	];

	public function ciudad()
	{
		return $this->belongsTo(Ciudades::class)->withDefault();
	}
}
