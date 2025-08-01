<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aliados extends Model
{
    use SoftDeletes;
    
	protected $table = 'aliados';
	
	public function aliados_validos()
	{
		return $this->hasMany('\App\Aliadosvalidos', 'aliados_id');
	}	
}
