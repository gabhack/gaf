<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Oficinas extends Model
{
    use SoftDeletes;
    
	protected $table = 'oficinas';
	
	public function ciudad()
	{
		return $this->hasOne('\App\Ciudades', 'id', 'ciudades_id');
	}
	
}
