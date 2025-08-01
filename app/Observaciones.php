<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Observaciones extends Model
{
    use SoftDeletes;
    
	protected $table = 'observaciones';
	
	public function estudio()
	{
		return $this->hasOne('\App\Estudiostr', 'id', 'estudios_id');
	}
	
	public function user()
	{
		return $this->hasOne('\App\User', 'id', 'users_id');
	}
	
}
