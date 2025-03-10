<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hego extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'nombre'
	];
}
