<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ami extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'nombre'
	];
}
