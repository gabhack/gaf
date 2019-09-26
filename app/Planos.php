<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Planos extends Model
{
    use SoftDeletes;
    
	protected $table = 'planos';
	
	public function pagaduria()
	{
		return $this->hasOne('\App\Pagadurias', 'id', 'pagadurias_id');
	}
	
}
