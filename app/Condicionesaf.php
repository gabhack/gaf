<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Condicionesaf extends Model
{
    use SoftDeletes;
    
	protected $table = 'condicionesaf';
	
}
