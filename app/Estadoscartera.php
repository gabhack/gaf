<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estadoscartera extends Model
{
    use SoftDeletes;
	
	protected $table = 'estadoscarteras';
}
