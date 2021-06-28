<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Consultas extends Model
{
    use SoftDeletes;
    
	protected $table = 'consultas';

	public function usuario()
	{
		return $this->hasOne('\App\User', 'id', 'users_id');
	}
}
