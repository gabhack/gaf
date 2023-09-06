<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Giro extends Model
{
	use SoftDeletes;

	protected $table = 'giros';
}
