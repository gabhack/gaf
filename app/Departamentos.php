<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departamentos extends Model
{
	use SoftDeletes;
    
	protected $table = 'departamentos';
	
	public function ciudades()
	{
		return $this->hasMany('\App\Ciudades', 'departamentos_id');
	}

}
