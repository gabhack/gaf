<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Motivosembargos extends Model
{
    use SoftDeletes;
    
	protected $table = 'motivosembargos';

	public function embargos()
	{
		return $this->hasMany('App\Embargos', 'id', 'motivos_id');
	}
}
